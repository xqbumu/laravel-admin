<?php

namespace Tests\Controllers;

use App\Http\Controllers\Controller;
use Encore\Incore\Controllers\ModelForm;
use Encore\Incore\Facades\Admin;
use Encore\Incore\Form;
use Encore\Incore\Grid;
use Encore\Incore\Layout\Content;
use Tests\Models\MultipleImage;

class MultipleImageController extends Controller
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
            $content->header('Upload image');

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
        return Docore::grid(Image::class, function (Grid $grid) {
            $grid->id('ID')->sortable();

            $grid->created_at();
            $grid->updated_at();

            $grid->disableFilter();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Docore::form(MultipleImage::class, function (Form $form) {
            $form->display('id', 'ID');

            $form->image('pictures')->multiple();

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
