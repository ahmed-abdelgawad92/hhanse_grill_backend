<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Karte;
class KarteEditRequest extends FormRequest
{
    private $id;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->id = $this->route('id');
        $item = Karte::find($this->id);
        return $item;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
          'number' => [
            'required',
            'integer',
            'unique:karte,number,'.$this->id
          ],
          'meal' => 'required',
          'price' => 'required|numeric'
        ];
    }
}
