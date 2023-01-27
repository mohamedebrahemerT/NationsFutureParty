<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\KnowledgeBaseCategories;
use App\Http\Requests\KnowledgeBase\KnowledgeBaseCategoryStore;
use App\Models\BaseModel;

class KnowledgeBaseCategory extends AccountBaseController
{
    
    public function create()
    {
        $this->categories = KnowledgeBaseCategories::all();
        return view('knowledge-base.create_category', $this->data);
    }

    public function store(KnowledgeBaseCategoryStore $request)
    {
        $category = new KnowledgeBaseCategories();
        $category->name = strip_tags($request->category_name);
        $category->save();
        $categoryData = KnowledgeBaseCategories::all();
        $options = BaseModel::options($categoryData, $category, 'name');

        return Reply::successWithData(__('messages.categoryAdded'), ['data' => $options]);
    }

    public function update(KnowledgeBaseCategoryStore $request, $id)
    {
        $category = KnowledgeBaseCategories::find($id);
        $category->name = strip_tags($request->category_name);
        $category->save();

        $categoryData = KnowledgeBaseCategories::all();

        return Reply::successWithData(__('messages.updatedSuccessfully'), ['data' => $categoryData]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = KnowledgeBaseCategories::findOrFail($id);
        $category->delete();
        $categoryData = KnowledgeBaseCategories::all();
        return Reply::successWithData(__('messages.categoryDeleted'), ['data' => $categoryData]);
    }

}
