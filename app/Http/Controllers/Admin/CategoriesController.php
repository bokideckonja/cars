<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class CategoriesController extends Controller
{
    /**
     * Prikazi kategorije.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::paginate();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Prikazi formu za kreiranje kategorije.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Sacuvaj kategoriju.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "name" => "required|max:255|unique:categories",
        ]);

        $category       = new Category;
        $category->name = $request->name;
        $category->slug = str_slug($request->slug);

        try{
            $category->save();
        }catch(\Exception $e){
            Log::error('Error saving category:'.$e->getMessage());

            session()->flash('flash-message', 'Error saving category.');
            session()->flash('flash-level', 'danger');
            return back()->withInput();
        }

        session()->flash('flash-message', 'Category successfully created.');
        return back();

    }

    /**
     * Prikazi formu za izmjenu kategorije.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Sacuvaj izmjene na kategoriji.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $this->validate($request, [
            "name" => [
                'required', 'max:255',
                Rule::unique('categories')->ignore($category->id),
            ],
        ]);

        $category->name = $request->name;
        $category->slug = str_slug($request->slug);

        try{
            $category->save();
        }catch(\Exception $e){
            Log::error('Error updating category:'.$e->getMessage());

            session()->flash('flash-message', 'Error updating category.');
            session()->flash('flash-level', 'danger');
            return back()->withInput();
        }

        session()->flash('flash-message', 'Category successfully updated.');
        return back();

    }

    /**
     * Obrisi kategoriju.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();

        session()->flash('flash-message', 'Category successfully deleted.');
        return back();
    }
}
