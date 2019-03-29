<?php

namespace App\Admin\Controllers;

use App\Models\Device;
use App\Models\DeviceFitment;
use App\Models\DeviceFunc;
use App\Models\Elevator;
use App\Http\Controllers\Controller;
use App\Models\ElevatorFitment;
use App\Models\ElevatorFunc;
use App\Models\Project;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Show;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class ElevatorSuperController extends Controller
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
            ->header('电梯审批')
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
            ->header('电梯审批')
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
            ->header('电梯审批')
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
            ->header('电梯审批')
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
        $grid->column('project.name','项目名称');
        $grid->column('project.city_id','区域');
        $grid->eid('电梯设备')->display(function(){
            return 'ID:'.implode('|',json_decode(json_encode($this->device), true));
        });
        $grid->num('电梯数量')->editable();
        $grid->height('提升高度');


        $grid->height('设备基价');
        $grid->height('功能加价');
        $grid->height('装修选项');
        $grid->height('运输费');
        $grid->height('设备超米费');
        $grid->height('非标单价');
        $grid->height('临时用梯设备费');
        $grid->height('设备税率');
        $grid->height('税率');
        $grid->height('非标单价');
        $grid->height('临时用梯费');


        $grid->height('安装基价');
        $grid->height('政府验收费');
        $grid->height('安装超米费');
        $grid->height('贯通门增加安装价');
        $grid->height('非标单价');
        $grid->height('二次验收费用');
        $grid->height('临时用梯安装费');
        $grid->height('安装税率');
        $grid->height('税率');

        $grid->height('备注');

        $grid->status('状态');
        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            // 在这里添加字段过滤器
            $pj=Project::where('status','=',0)->get();
            $arr=Arr::pluck($pj, 'name','id');
            //var_dump($pj,$arr);
            $filter->equal('pid','项目')->select($arr);
            $filter->like('desc', '电梯说明');
        });
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });
        $grid->disableExport();
        $grid->disableCreateButton();
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            //$actions->disableView();
            $actions->disableEdit();
            $actions->disableDelete();
        });
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

        $pj=Project::where('status','=',0)->get();
        $arr=Arr::pluck($pj, 'name','id');
        //var_dump($pj,$arr);
        $form->select('pid','项目')->options($arr)->required();
        $form->divide();
        if($hasEdit){
            $form->display('device','已选电梯设备')->with(function ($value) {
                return 'ID:'.implode('|',json_decode(json_encode($value), true));
            });
            $form->select('_brand','电梯品牌')->options('/admin/device/brands')
                ->load('did', '/admin/device/brandsDetail');
            $form->select('did','电梯设备');
        }else{
            $form->select('_brand','电梯品牌')->options('/admin/device/brands')
                ->load('did', '/admin/device/brandsDetail');
            $form->select('did','电梯设备')->required();
        }
        $form->divide();

        $form->number('num','电梯数量')->min(1)->required();
        $form->number('height','提升高度')->min(1)->required();
        $form->text('layer_number','层/站/门数');
        $form->number('pit_depth','底坑深度mm');
        $form->number('top_height','顶层高度mm');

        $form->number('hall_width','厅门尺寸（mm）宽');
        $form->number('hall_height','厅门尺寸（mm）高');

        $form->number('car_width','轿厢尺寸（mm）宽');
        $form->number('car_height','轿厢尺寸（mm）高');
        $form->number('car_depth','轿厢尺寸（mm）深');

        $form->text('desc','电梯说明');

        //忽略字段
        $form->ignore(['_brand']);
        $form->saving(function (Form $form){
            $form->did=$form->did>0?$form->did:$form->model()->did;
        });

        return $form;
    }
    protected function detail_sm($eid,$content)
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
            });
        $show->func('已配备功能', function ($grid) use($eid) {
            $grid->resource('/admin/elevatorFunc');
            $grid->id('ID');
            $grid->name('功能名称');
            $grid->unit('功能单位');
            $grid->desc('功能描述');
            $grid->num('数量')->editable();
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
            $grid->resource('/admin/elevatorFitment');
            $grid->id('ID');
            $grid->name('装饰项目名称');
            $grid->stuff('材料');
            $grid->spec('规格编号');
            $grid->desc('描述');
            $grid->num('数量')->editable();
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
        $fids=[];
        $dfr=DB::table('device_func_rela')->where('did',$did)->get();
        foreach($dfr as $d){
            array_push($fids,$d->fid);
        }
        $grid->model()->whereIn('id',$fids);
        $grid->id('ID');
        $grid->name('功能名称');
        $grid->unit('功能单位');
        $grid->desc('功能描述');
        $grid->xxxx('操作')->fitout('fun');
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

        $grid->disableActions();
        $grid->disablePagination();
        $grid->disableTools();
        $grid->paginate(100);
        return $grid;
    }
    protected function fitGrid($did)
    {
        $grid = new Grid(new DeviceFitment);
        $fids=[];
        $dfr=DB::table('device_fitment_rela')->where('did',$did)->get();
        foreach($dfr as $d){
            array_push($fids,$d->fid);
        }
        $grid->model()->whereIn('id',$fids);
        $grid->id('ID');
        $grid->name('装饰项目名称');
        $grid->stuff('材料');
        $grid->spec('规格编号');
        $grid->desc('描述');
        $grid->yyyy('操作')->fitout('fit');
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

        $grid->disableActions();
        $grid->disablePagination();
        $grid->disableTools();
        $grid->paginate(100);
        return $grid;
    }
    public function funfit($eid, Content $content){
        $content
            ->header('电梯审批')
            ->description('选功能、选装修')
            ->body($this->detail_sm($eid,$content));
        $content->row('<h3>请从下方选择功能和装修</h3>');
        $content->row(function(Row $row) use($eid) {
            $ele=Elevator::findOrFail($eid);
            $row->column(6, $this->funGrid($ele->did));
            $row->column(6, $this->fitGrid($ele->did));
        });
        return $content;
    }
    public function fitout($eid,$fid){
        $type=Input::get('type');
        $data=['fid'=>$fid,'eid'=>$eid];
        if($type=='fun'){
            ElevatorFunc::create($data);
        }
        if($type=='fit'){
            ElevatorFitment::create($data);
        }
        return $data;
    }
}
