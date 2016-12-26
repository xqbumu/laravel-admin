<?php

namespace Tests\Controllers;

use App\Http\Controllers\Controller;
use Encore\Incore\Controllers\ModelForm;
use Encore\Incore\Facades\Admin;
use Encore\Incore\Form;
use Encore\Incore\Grid;
use Encore\Incore\Layout\Content;
use Tests\Models\File;

class FileController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Docore::content(function (Content $content) {
            $content->header('header');
            $content->description('description');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     *
     * @return Content
     */
    public function edit($id)
    {
        return Docore::content(function (Content $content) use ($id) {
            $content->header('header');
            $content->description('description');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Docore::content(function (Content $content) {
            $content->header('Upload file');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Docore::grid(File::class, function (Grid $grid) {
            $grid->id('ID')->sortable();

            $grid->created_at();
            $grid->updated_at();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Docore::form(File::class, function (Form $form) {
            $form->display('id', 'ID');

            $form->file('file1');
            $form->file('file2');
            $form->file('file3');
            $form->file('file4');
            $form->file('file5');
            $form->file('file6');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
