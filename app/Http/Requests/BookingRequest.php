<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
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
        $event = \App\Models\Event::find($this->event_id);
        return [
            'event_id' => 'required|exists:events,id',
            'number_of_seats' => 'required|integer|min:1|max:' . ($event ? $event->max_attendees : 0), // Vérifiez le maximum de places
        ];
    }

    public function messages()
    {
        return [
            'event_id.required' => 'L\'événement est requis.',
            'event_id.exists' => 'Cet événement n\'existe pas.',
            'number_of_seats.required' => 'Le nombre de places est requis.',
            'number_of_seats.integer' => 'Le nombre de places doit être un nombre.',
            'number_of_seats.min' => 'Le nombre de places doit être au moins 1.',
            'number_of_seats.max' => 'Le nombre de places ne peut pas dépasser le nombre maximum autorisé.',
        ];
    }
}
