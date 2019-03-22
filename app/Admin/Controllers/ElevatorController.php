<?php

namespace App\Admin\Controllers;

use App\Models\DeviceFitment;
use App\Models\DeviceFunc;
use App\Models\Elevator;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Show;

class ElevatorController extends Controller
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
            ->header('电梯管理')
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
            ->header('电梯管理')
            ->description('详情')
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
            ->header('电梯管理')
            ->description('修改')
            ->body($this->form(true)->edit($id));
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
            ->header('电梯管理')
            ->description('新增')
            ->body($this->form(false));
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Elevator);

        $grid->id('ID');
        $grid->eid('电梯设备')->display(function(){
            return 'ID:'.implode('|',json_decode(json_encode($this->device), true));
        });
        $grid->layer_number('层/站/门数');
        $grid->pit_depth('底坑深度mm');
        $grid->top_height('顶层高度mm');
        $grid->hall_width('厅门尺寸（mm）')->display(function($hall){
            return "{$this->hall_width}X{$this->hall_height}";
        });
        $grid->car_width('轿厢尺寸（mm）')->display(function($car){
            return "{$this->car_width}X{$this->car_height}X{$this->car_depth}";
        });
        $grid->desc('电梯说明');
        $grid->status1('功能/装修')->display(function(){
            return '<a href="/admin/elevator/'.$this->id.'/funfit">配备</a>';
        });
        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            // 在这里添加字段过滤器
            $filter->like('desc', '电梯说明');
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
        $show = new Show(Elevator::findOrFail($id));

        $show->id('ID');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form($hasEdit=false)
    {
        $form = new Form(new Elevator);

        if($hasEdit){
            $form->display('device','已选电梯设备')->with(function ($value) {
                return 'ID:'.implode('|',json_decode(json_encode($value), true));
            });
        }
        $form->select('_brand','电梯品牌')->options('/admin/device/brands')
            ->load('did', '/admin/device/brandsDetail');
        $form->select('did','电梯设备');
        $form->divide();

        $form->text('layer_number','层/站/门数');
        $form->number('pit_depth','底坑深度mm');
        $form->number('top_height','顶层高度mm');

        $form->number('hall_width','厅门尺寸（mm）长');
        $form->number('hall_width','厅门尺寸（mm）宽');

        $form->number('car_width','轿厢尺寸（mm）长');
        $form->number('car_height','轿厢尺寸（mm）宽');
        $form->number('car_depth','轿厢尺寸（mm）深');

        $form->text('desc','电梯说明');

        //忽略字段
        $form->ignore(['_brand']);
        $form->saving(function (Form $form){
            $form->did=$form->did>0?$form->did:$form->model()->did;
        });

        return $form;
    }
    protected function detail_sm($eid)
    {
        $show = new Show(Elevator::findOrFail($eid));
        $show->desc('描述');
        $model=$show->getModel();
        $show->panel()
            ->title('ID:'.implode('|',json_decode(json_encode($model->device), true)))
            ->tools(function ($tools) {
                $tools->disableEdit();
                $tools->disableList();
                $tools->disableDelete();
            });;
        $show->func('已配备功能', function ($grid) use($eid) {
            $grid->resource('/admin/deviceFunc');
            $grid->id('ID');
            $grid->name('功能名称');
            $grid->unit('功能单位');
            $grid->desc('功能描述');
            //$grid->elevator()->limit(50);
            //$grid->disableActions();
            $grid->disablePagination();
            $grid->disableCreateButton();
            $grid->disableFilter();
            $grid->disableRowSelector();
            $grid->disableTools();
            $grid->disableExport();
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableView();
                $actions->disableEdit();
                //$actions->disableDelete();
            });
            $grid->paginate(100);
        });
        $show->fitment('已配备装修', function ($grid) use($eid) {
            $grid->resource('/admin/deviceFitment');
            $grid->model()->where('eid','=',$eid);
            $grid->id('ID');

            //$grid->elevator()->limit(50);
            //$grid->disableActions();
            $grid->disablePagination();
            $grid->disableCreateButton();
            $grid->disableFilter();
            $grid->disableRowSelector();
            $grid->disableTools();
            $grid->disableExport();
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableView();
                $actions->disableEdit();
                //$actions->disableDelete();
            });
            $grid->paginate(100);
        });
        return $show;
    }
    protected function funGrid($did)
    {
        $grid = new Grid(new DeviceFunc);
        $grid->model()->where('did','=',$did);
        $grid->id('ID');
        $grid->name('功能名称');
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
        $grid->disableCreateButton();

        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableView();
            $actions->disableEdit();
            $actions->disableDelete();
        });
        return $grid;
    }
    protected function fitGrid($did)
    {
        $grid = new Grid(new DeviceFitment);
        $grid->model()->where('did','=',$did);

        $grid->id('ID');
        $grid->name('装饰项目名称');
        $grid->stuff('材料');
        $grid->spec('规格编号');
        $grid->unit('单位');
        $grid->desc('描述');

        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            // 在这里添加字段过滤器
            $filter->like('name', '装饰项目名称');
            $filter->like('stuff', '材料');
            $filter->like('spec', '规格编号');
        });

        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });
        $grid->disableExport();
        $grid->disableCreateButton();

        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableView();
            $actions->disableEdit();
            $actions->disableDelete();
        });
        return $grid;
    }
    public function funfit($eid, Content $content){
        $content
            ->header('电梯管理')
            ->description('选功能、选装修')
            ->body($this->detail_sm($eid));
        $ele=Elevator::findOrFail($eid);
        $content->row('<h3>请从下方选择功能和装修</h3>');
        $content->row($this->funGrid($ele->did));
        $content->row($this->fitGrid($ele->did));
        return $content;
    }
}
