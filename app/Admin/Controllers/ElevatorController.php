<?php

namespace App\Admin\Controllers;

use App\Models\Device;
use App\Models\Elevator;
use App\Http\Controllers\Controller;
use App\Models\ElevatorFitment;
use App\Models\ElevatorFunc;
use App\Models\Fitment;
use App\Models\Funtion;
use App\Models\Project;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Show;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Mockery\Exception;

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
            ->header('电梯报备')
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
            ->header('电梯报备')
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
            ->header('电梯报备')
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
            ->header('电梯报备')
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
        $grid->region('梯号');
        $grid->eid('电梯设备')->display(function(){
            return $this->device->brand.$this->device->brand_set;
        });
        $grid->num('电梯数量');
        $grid->height('提升高度(m)');
        $grid->layer_numbers('层/站/门')->display(function($layer_number){
            return "{$this->layer_number}/{$this->layer_number_site}/{$this->layer_number_door}";
        });
        $grid->pit_depth('底坑深度(mm)');
        $grid->top_height('顶层高度(mm)');
        $grid->hall_width('厅门尺寸（mm）')->display(function($hall){
            return "{$this->hall_width}X{$this->hall_height}";
        });
        $grid->car_width('轿厢尺寸（mm）')->display(function($car){
            return "{$this->car_width}X{$this->car_height}X{$this->car_depth}";
        });
        $grid->desc('电梯说明');

        $grid->status1('功能/装修')->display(function(){
            if(Admin::user()->isRole('localcom') && $this->status>Elevator::STATUS_YTJ){
                return Elevator::getStatusStr($this->status);
            }
            return '<a href="/admin/elevator/'.$this->id.'/funfit">查看&配置</a>';
        });
        $grid->status('状态')->eleStatus();
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
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableView();
            if(Admin::user()->isRole('localcom') && $actions->row->status>Elevator::STATUS_YTJ){
                $actions->disableDelete();
                $actions->disableEdit();
            }
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
        $ele=Elevator::findOrFail($id);
        $ele->status=Elevator::STATUS_YTJ;
        $ele->save();
        return "200";
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
        $form->text('region','梯号');
        $form->divide();
        if($hasEdit){
            $form->display('device','已选电梯设备')->with(function ($value) {
                return 'ID:'.implode('|',json_decode(json_encode($value), true));
            });
        }
        $dsArr=[];
        foreach(Device::groupBy('brand','brand_set')->get() as $d){
            $dsArr[$d->brand.'/'.$d->brand_set]=$d->brand.'/'.$d->brand_set;
        }
        $form->select('q_brand_set','品牌系列')->options($dsArr)->required();

        $dsArr=[];
        foreach(Device::groupBy('dload')->get() as $d){
            $dsArr[$d->dload]=$d->dload;
        }
        $form->select('q_dload','载重')->options($dsArr)->required();
        $dsArr=[];
        foreach(Device::groupBy('speedup')->get() as $d){
            $dsArr[$d->speedup]=$d->speedup;
        }
        $form->select('q_speedup','速度')->options($dsArr)->required();
        $form->number('layer_number','层站')->min(1)->required()->default(2);
        $form->divide();

        $form->number('num','电梯数量')->min(1)->required();
        $form->number('height','提升高度（m）')->min(1)->required()->help('总爬升高度');
        //$form->number('layer_number','层数')->min(1)->required();
        $form->number('layer_number_site','站数')->min(1)->required();
        $form->number('layer_number_door','门数')->min(1)->required();
        $form->number('pit_depth','底坑深度（mm）');
        $form->number('top_height','顶层高度（mm）');

        $form->number('hall_width','厅门尺寸（mm）宽');
        $form->number('hall_height','厅门尺寸（mm）高');

        $form->number('car_width','轿厢尺寸（mm）宽');
        $form->number('car_height','轿厢尺寸（mm）高');
        $form->number('car_depth','轿厢尺寸（mm）深');

        $states = [
            'on'  => ['value' => 1, 'text' => '有', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '没有', 'color' => 'danger'],
        ];
        $form->switch('has_through_door','是否有贯通门')->states($states);
        $form->text('desc','电梯说明');

        $form->divide();
        $form->file('file_1','附件1');
        $form->html(function (){
            if($this->file_1){
                return '<a href="'.(config('filesystems.disks.qiniu.url').$this->file_1).'" target="_blank">附件1: '.$this->file_1.'</a>';
            }
        }, $label = '');
        $form->file('file_2','附件2');
        $form->html(function (){
            if($this->file_2){
                return '<a href="'.(config('filesystems.disks.qiniu.url').$this->file_2).'" target="_blank">附件2: '.$this->file_2.'</a>';
            }
        }, $label = '');
        $form->file('file_3','附件3');
        $form->html(function (){
            if($this->file_3){
                return '<a href="'.(config('filesystems.disks.qiniu.url').$this->file_3).'" target="_blank">附件3: '.$this->file_3.'</a>';
            }
        }, $label = '');

        //忽略字段
        //$form->ignore(['_brand']);
        $form->saving(function (Form $form){
            //$form->did=$form->did>0?$form->did:$form->model()->did;
            $form->did='';
            if($form->q_brand_set && $form->q_dload && $form->q_speedup && $form->layer_number){
                $q_brand_set=explode('/',$form->q_brand_set);
                $query=[
                    'brand'=>array_shift($q_brand_set),
                    'brand_set'=>implode('/',$q_brand_set),
                    'dload'=>$form->q_dload,
                    'speedup'=>$form->q_speedup,
                    'floor'=>$form->layer_number,
                ];
                //DB::connection()->enableQueryLog();
                $did=Device::where($query)->value('id');
                //dd($did,DB::getQueryLog());
                //throw new Exception(DB::getQueryLog());
                if($did){
                    $form->did=$did;
                }
            }else{
                throw new Exception('请完善电梯参数');
            }
            if(!$form->did){
                throw new Exception('未找到电梯设备,请核对电梯参数');
            }
        });
        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();
        });
        $form->footer(function ($footer) {
            // 去掉`查看`checkbox
            $footer->disableViewCheck();
            // 去掉`继续编辑`checkbox
            $footer->disableEditingCheck();
            // 去掉`继续创建`checkbox
            $footer->disableCreatingCheck();
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
        return $show;
    }
    protected function fitGridChecked($eid,$device){
        $grid = new Grid(new ElevatorFitment);

        $grid->resource('/admin/elevatorFitment');

        $grid->model()->join('fitment', 'fitment.id', '=', 'elevator_fitment.fid');
        $grid->model()->where('fitment.has_in_base',0);
        $grid->model()->where('eid',$eid);
        $grid->model()->orderby('elevator_fitment.id','desc');
        $select="elevator_fitment.id as id,elevator_fitment.num"
            .",fitment.name as `name`,fitment.stuff as stuff,fitment.desc as `desc`"
            .",fitment.spec as `spec`";
        $grid->model()->select(DB::raw($select));

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
        $grid->footer(function ($query) {
            $js=<<<JSEND
<a class='tbf-fitment'></a>
<script>
$(function(){
  var tf=$('.tbf-fitment').parents('.box-footer');
  var tb=tf.prev();
  tb.css({height:'300px'});
  tf.remove();
})
</script>
JSEND;

            return $js;
        });
        return $grid;
    }
    protected function funGridChecked($eid,$device){
        $grid = new Grid(new elevatorFunc);

        $grid->resource('/admin/elevatorFunc');
        $grid->model()->join('funtion', 'funtion.id', '=', 'elevator_func.fid');
        $grid->model()->where('funtion.has_in_base',0);
        $grid->model()->where('eid',$eid);
        $grid->model()->orderby('elevator_func.id','desc');
        $select="elevator_func.id as id,elevator_func.num"
            .",funtion.name as `name`,funtion.unit as unit,funtion.desc as `desc`";
        $grid->model()->select(DB::raw($select));
        $grid->column('id','ID');
        $grid->column('name','功能名称');
        $grid->column('unit','功能单位');
        $grid->column('desc','功能描述');
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
        $grid->footer(function ($query) {
            $js=<<<JSEND
<a class='tbf-func'></a>
<script>
$(function(){
  var tf=$('.tbf-func').parents('.box-footer');
  var tb=tf.prev();
  tb.css({height:'300px'});
  tf.remove();
})
</script>
JSEND;

            return $js;
        });
        return $grid;
    }
    protected function funGrid($eid,$device)
    {
        $grid = new Grid(new Funtion);
        $grid->model()->whereRaw("id not in (select fid from elevator_func where eid='{$eid}')");
        $grid->model()->where(function ($query) use($device){
            $query->where("brand",$device->brand);
            $query->whereRaw("find_in_set('{$device->brand}/{$device->brand_set}',`brand_set`)");
            $query->whereRaw("find_in_set('{$device->dload}',`dload`)");
            $query->whereRaw("find_in_set('{$device->speedup}',`speedup`)");
        });
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
        $grid->paginate(200);
        $grid->footer(function ($query) {
            $js=<<<JSEND
<a class='tbf-funGrid'></a>
<script>
$(function(){
  var tf=$('.tbf-funGrid').parents('.box-footer');
  var tb=tf.prev();
  tb.css({height:'500px'});
  tf.remove();
})
</script>
JSEND;

            return $js;
        });
        return $grid;
    }
    protected function fitGrid($eid,$device)
    {
        $grid = new Grid(new Fitment);
        $grid->model()->whereRaw("id not in (select fid from elevator_fitment where eid='{$eid}')");
        $grid->model()->where('brand',$device->brand);
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
        $grid->paginate(200);
        $grid->footer(function ($query) {
            $js=<<<JSEND
<a class='tbf-fitGrid'></a>
<script>
$(function(){
  var tf=$('.tbf-fitGrid').parents('.box-footer');
  var tb=tf.prev();
  tb.css({height:'500px'});
  tf.remove();
})
</script>
JSEND;

            return $js;
        });
        return $grid;
    }
    public function funfit($eid, Content $content){
        $content
            ->header('电梯报备')
            ->description('选功能、选装修')
            ->body($this->detail_sm($eid,$content));

        $content->row(function(Row $row) use($eid) {
            $ele=Elevator::findOrFail($eid);
            $row->column(6, $this->funGridChecked($eid,$ele->device));
            $row->column(6, $this->funGrid($eid,$ele->device));
        });
        $content->row(function(Row $row) use($eid) {
            $ele=Elevator::findOrFail($eid);
            $row->column(6, $this->fitGridChecked($eid,$ele->device));
            $row->column(6, $this->fitGrid($eid,$ele->device));
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
