<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreTaskRequest extends FormRequest
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
            'title'=> ['required','string','min:3','max:255'],
            'description' =>['required','string','min:10','max:2000'],
            'priority' =>['required','string','in:highest,high,medium,low,lowest'],
            'assigned_to' => ['required','integer','exists:users,user_id'],
            'status' => ['required','string','in:To Do,In progress,Done'],
            'due_date' => ['required','date'],
        ];
    }

     /**
     * Get the custom error messages for the validator.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => 'حقل :attribute مطلوب ',
            'string' => 'حقل :attribute يجب أن يكون نصا وليس أي نوع آخر',
            'max' => 'عدد محارف :attribute لا يجب أن يتجاوز 255 محرفا',
            'description.max' => 'لا يجب ان يتجاوز :attribute 2000 محرفا',
            'min' => 'حقل :attribute يجب أن يكون 3 محارف على الأقل',
            'description.min' => 'عدد محارف :attribute لا يقل عن 10 محارف',
            'priority.in' => 'حقل :attribute يجب أن يكون واحدًا من القيم التالية: highest, high,medium,low, lowest',
            'status.in' => 'حقل :attribute يجب أن يكون واحدًا من القيم التالية: To Do, progress, Done', 
            'date' => 'حقل :attribute يجب ان يكون بصيغة تاريخ ',
        ];
    }

    /**
     * Get custom attribute names for error messages.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title' => 'عنوان المهمة',
            'description' => 'المهمة',
            'priority' => 'الأولوية',
            'assigned_to' => 'مسند الى ',
            'status' => 'الحالة',
            'due_date'=> 'تاريخ الانجاز'
        ];
    }

    protected function prepareForValidation()
    {    
            //In input form user input the name of the person to assign the task to and here we get the object of this person to get the id of it 
            $user=User::where('name',$this->input('assigned_to'))->first();
            $this->merge([
                'title' => ucwords(strtolower($this->input('title'))),
                'description' => ucwords(strtolower($this->input('description'))),
                'assigned_to' => $user->id,
            ]);
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status'  => 'error',
            'message' => 'Validation failed.',
            'errors'  => $validator->errors(),
        ], 422));
    }
}

