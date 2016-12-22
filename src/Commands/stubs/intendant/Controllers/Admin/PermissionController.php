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
            $content->header(trans('docore::lang.permissions'));
            $content->description(trans('docore::lang.list'));
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
            $content->header(trans('docore::lang.permissions'));
            $content->description(trans('docore::lang.edit'));
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
            $content->header(trans('docore::lang.permissions'));
            $content->description(trans('docore::lang.create'));
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
            $grid->slug(trans('docore::lang.slug'));
            $grid->name(trans('docore::lang.name'));

            $grid->created_at(trans('docore::lang.created_at'));
            $grid->updated_at(trans('docore::lang.updated_at'));

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

            $form->text('slug', trans('docore::lang.slug'))->rules('required');
            $form->text('name', trans('docore::lang.name'))->rules('required');

            $form->display('created_at', trans('docore::lang.created_at'));
            $form->display('updated_at', trans('docore::lang.updated_at'));
        });
    }
}
