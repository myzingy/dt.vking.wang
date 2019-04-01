<?php

namespace App\Admin\Controllers;

use App\Models\Device;
use App\Models\DeviceFitment;
use App\Models\DeviceFunc;
use App\Models\Elevator;
use App\Http\Controllers\Controller;
use App\Models\ElevatorFitment;
use App\Models\ElevatorFunc;
use App\Models\ElevatorPrice;
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
use Illuminate\Support\Facades\Redirect;

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
        $content
            ->header('电梯审批')
            ->description('详情');
        $content->body($this->elevatorDetail($id,$content));
        return $content;
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
        $grid->column('project.name','项目名称')->style('min-width:100px');
        $grid->region('区域')->style('min-width:60px');
        $grid->eid('电梯设备')->display(function(){
            return $this->device->brand.$this->device->brand_set;
        })->style('min-width:80px');
        $grid->height('提升高度')->style('min-width:50px');

        $devicebg='background:#eee;';
        $grid->column('expe.设备基价','设备基价')->style("min-width:50px;$devicebg");
        $grid->column('expe.设备功能加价','功能加价')->style("min-width:50px;$devicebg");
        $grid->column('expe.设备装修选项','装修选项')->style("min-width:50px;$devicebg");
        $grid->column('expe.设备运输费','运输费')->style("min-width:50px;$devicebg");
        $grid->column('expe.设备超米费','设备超米费')->style("min-width:60px;$devicebg");
        $grid->column('expe.设备非标单价','非标单价')->style("min-width:50px;$devicebg");
        $grid->column('expe.设备临时用梯费','临时用梯费')->style("min-width:60px;$devicebg");
        $grid->column('expe.设备税率计算','设备单价')->style("min-width:50px;$devicebg;font-weight:bold;");

        $devicebg='background:#3e3;';
        $grid->column('expe.安装基价','安装基价')->style("min-width:50px;$devicebg");
        $grid->column('expe.政府验收费','政府验收费')->style("min-width:60px;$devicebg");
        $grid->column('expe.安装超米费','安装超米费')->style("min-width:60px;$devicebg");
        $grid->column('expe.贯通门增加安装价','贯通门增加安装价')->style("min-width:60px;$devicebg");
        $grid->column('expe.安装非标单价','非标单价')->style("min-width:50px;$devicebg");
        $grid->column('expe.二次验收费用','二次验收费用')->style("min-width:60px;$devicebg");
        $grid->column('expe.安装临时用梯费','临时用梯费')->style("min-width:60px;$devicebg");
        $grid->column('expe.安装税率计算','安装单价')->style("min-width:50px;$devicebg;font-weight:bold;");

        $grid->column('expe.小计','小计')
            ->display(function(){
                if(!$this->expe) return 0;
                return $this->expe->设备税率计算+$this->expe->安装税率计算;
            })
            ->style("min-width:50px;");
        $grid->num('台数')->style('min-width:50px');
        $grid->dc('设备合计')
            ->display(function(){
                if(!$this->expe) return 0;
                return $this->expe->设备税率计算*$this->num;
            })
            ->style('min-width:50px');
        $grid->ic('安装合计')
            ->display(function(){
                if(!$this->expe) return 0;
                return $this->expe->安装税率计算*$this->num;
            })
            ->style('min-width:50px');
        $grid->zj('总计')
            ->display(function(){
                if(!$this->expe) return 0;
                return ($this->expe->设备税率计算+$this->expe->安装税率计算)*$this->num;
            })
            ->style('min-width:50px');

        $grid->status('状态')->using(Elevator::STATUS)->style("min-width:50px;");
        $grid->column('expe.desc','备注')->style("min-width:50px;");

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
    public function elevatorDetail($eid){
        $ele=Elevator::findOrFail($eid);
        $expe=new ElevatorPrice(['eid'=>$eid]);
        $expe->runExpe();
        $view=view('elevatorDetail',['ele'=>$ele]);
        return $view;
    }
    public function setPrice($eid){
        $ele=Elevator::findOrFail($eid);
        if(!$ele){
            return Redirect::to("/admin/elevatorSuper");
        }
        $data=Input::get();
        $act=$data['_act'];
        $data=Arr::except($data,['_token','_act']);

        $ep=ElevatorPrice::where('eid',$eid);
        if($act=='device' && $ele->status<Elevator::STATUS_SBS){
            $ele->status=Elevator::STATUS_SBS;
        }
        if($act=='install'&& $ele->status<Elevator::STATUS_ANS){
            $ele->status=Elevator::STATUS_ANS;
        }
        $ele->save();
        $ep->update($data);
        $expe=new ElevatorPrice(['eid'=>$eid]);
        $expe->runExpe(true);
        //return Redirect::back(302);
        return Redirect::refresh(302);
    }
}
