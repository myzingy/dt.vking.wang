<?php

namespace App\Admin\Controllers;

use App\Models\DeviceFunc;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class DeviceFuncController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('电梯功能价')
            ->description('列表')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('电梯功能价')
            ->description('详细')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('电梯功能价')
            ->description('修改')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('电梯功能价')
            ->description('新增')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DeviceFunc);

        $grid->id('ID');
        $grid->device('电梯设备')->display(function ($device) {
            return 'ID:'.implode('|',json_decode(json_encode($device), true));
        });
        $grid->name('功能名称');
        $grid->price('功能加价');
        $grid->has_in_base('是否标配/是否在基础价格包含');
        $grid->unit('功能单位');
        $grid->desc('功能描述');

        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            // 在这里添加字段过滤器
            $filter->like('name', '功能名称');
        });
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });
        $grid->disableExport();
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(DeviceFunc::findOrFail($id));

        $show->id('ID');
        $show->device('电梯设备')->as(function ($device) {
            return 'ID:'.implode('|',json_decode(json_encode($device), true));
        });
        $show->name('功能名称');
        $show->price('功能加价');
        $show->has_in_base('是否标配/是否在基础价格包含');
        $show->unit('功能单位');
        $show->desc('功能描述');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new DeviceFunc);
        $form->display('device','已选电梯设备')->with(function ($value) {
            return 'ID:'.implode('|',json_decode(json_encode($value), true));
        });
        $form->select('_brand','电梯品牌')->options('/admin/device/brands')
            ->load('did', '/admin/device/brandsDetail');
        $form->select('did','电梯设备');
        $form->divide();

        $form->text('name','功能名称');
        $form->text('price','功能加价');
        $form->text('has_in_base','是否标配/是否在基础价格包含');
        $form->text('unit','功能单位');
        $form->text('desc','功能描述');

        //忽略字段
        $form->ignore(['_brand']);
        $form->saving(function (Form $form){
            $form->did=$form->did>0?$form->did:$form->model()->did;
        });
        return $form;
    }
}
