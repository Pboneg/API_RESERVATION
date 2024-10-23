<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'event_date' => 'required|date|after:today',
            'max_attendees' => 'required|integer|min:1',
            'available_seats' => 'required|integer|min:0|max:'. request('max_attendees'), // On s'assure que les sièges disponibles ne dépassent pas le max
            'reservations_closed' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'user_id' => 'nullable'
        ];
    }

    /**
     * Messages d'erreur personnalisés.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Le titre est requis.',
            'description.required' => 'La description est requise.',
            'event_date.after' => 'La date de l\'événement doit être une date future.',
            'available_seats.max' => 'Le nombre de sièges disponibles ne peut pas dépasser le nombre maximum de participants.',
        ];
    }
}


