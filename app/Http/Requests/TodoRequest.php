<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TodoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // ルートパラメータからtodo_listのIDを取得
        $todoListId = $this->route('todo_list'); // ルート名がtodo_listの場合

        if (!$todoListId) {
            // 作成(store)の場合は認可不要
            return true;
        }

        // TodoListモデルを取得し、ポリシーを呼び出す
        $todoList = TodoList::findOrFail($todoListId);

        // 認証ユーザーがこのTodoリストを操作する権限があるかをチェック
        // update、deleteのアクション名でポリシーのメソッドが呼び出されます
        return $this->user()->can('update', $todoList); 
        // または return $this->user()->can('delete', $todoList);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
