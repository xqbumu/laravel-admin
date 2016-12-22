<?php

namespace Intendant\{$stub_intendant_zone_upper}\Controllers\Incore;

use Intendant\{$stub_intendant_zone_upper}\Auth\Database\Permission;
use Intendant\{$stub_intendant_zone_upper}\Facades\Incore;

use Inchow\Incore\Form;
use Inchow\Incore\Grid;
use Inchow\Incore\Layout\Content;

use Illuminate\Routing\Controller;

class PermissionController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Incore::content(function (Content $content) {
            $content->header(trans('incore::lang.permissions'));
            $content->description(trans('incore::lang.list'));
            $content->body($this->grid()->render());
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
        return Incore::content(function (Content $content) use ($id) {
            $content->header(trans('incore::lang.permissions'));
            $content->description(trans('incore::lang.edit'));
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
        return Incore::content(function (Content $content) {
            $content->header(trans('incore::lang.permissions'));
            $content->description(trans('incore::lang.create'));
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
        return Incore::grid(Permission::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->slug(trans('incore::lang.slug'));
            $grid->name(trans('incore::lang.name'));

            $grid->created_at(trans('incore::lang.created_at'));
            $grid->updated_at(trans('incore::lang.updated_at'));

            $grid->disableBatchDeletion();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        return Incore::form(Permission::class, function (Form $form) {
            $form->display('id', 'ID');

            $form->text('slug', trans('incore::lang.slug'))->rules('required');
            $form->text('name', trans('incore::lang.name'))->rules('required');

            $form->display('created_at', trans('incore::lang.created_at'));
            $form->display('updated_at', trans('incore::lang.updated_at'));
        });
    }
}
