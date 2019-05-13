<?php

namespace App\Admin\Controllers;

use App\Models\Device;
use App\Models\Elevator;
use App\Models\Project;
use App\Http\Controllers\Controller;
use App\Models\ProjectElevator;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Show;

class ProjectController extends Controller
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
            ->header('项目管理')
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
            ->header('项目管理')
            ->description('详情');
        $content->body($this->projectDetail($id,$content));
        //$content->body($this->projectElevatorDetail($id));
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
            ->header('项目管理')
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
        $content
            ->header('项目管理')
            ->description('新增');
        $content->body($this->form());
        return $content;
    }
    private function eleGrid(){
        $grid = new Grid(new Elevator);
        $grid->model()->orderBy('id','desc');
        $grid->id('ID');
        $grid->device('电梯设备')->display(function ($device) {
            return 'ID:'.implode('|',json_decode(json_encode($device), true));
        });
        $grid->layer_number('层/站/门数');
        $grid->pit_depth('底坑深度mm');
        $grid->top_height('顶层高度mm');
        $grid->hall_width('厅门尺寸（mm）');
        $grid->car_width('轿厢尺寸（mm）');
        $grid->desc('电梯说明');
        $grid->column('id_x','配备到项目')->fitout();

        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            // 在这里添加字段过滤器
            $filter->like('name', '项目名称');
            $filter->where(function ($query) {
                $query->where('addr', 'like', "%{$this->input}%")
                    ->orWhere('province_id', 'like', "%{$this->input}%")
                    ->orWhere('city_id', 'like', "%{$this->input}%")
                    ->orWhere('district_id', 'like', "%{$this->input}%");
            }, '地址');
            $filter->like('first_party', '甲方名称');
            $filter->like('artisan_man', '技术负责人');
            $filter->like('price_man', '成本负责人');
            $filter->in('orientation', '项目定位')->checkbox(Project::GABC);
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
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Project);
        $grid->id('ID');
        $grid->name('项目名称');
        $grid->addr('详细地址')->display(function($addr){
            return $this->province_id.$this->city_id.$this->district_id.$addr;
        });
        $grid->first_party('甲方名称');
        $grid->artisan_man('技术负责人');
        $grid->price_man('成本负责人');
        $grid->desc('项目简介');
        $grid->orientation('项目定位')->display(function($c){
            return implode(',',$c);
        });
        $grid->status('状态');
        $grid->elevator('电梯')->display(function($elevators){
            $html='';
            foreach ($this->elevator as $ele ){
                $device=$ele->device->brand.$ele->device->brand_set;
                $html.="<p>({$device}){$ele->desc}<font color='red'>（{$ele->num}部）</font></p>";
            }
            return $html;
        });

        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            // 在这里添加字段过滤器
            $filter->like('name', '项目名称');
            $filter->where(function ($query) {
                $query->where('addr', 'like', "%{$this->input}%")
                    ->orWhere('province_id', 'like', "%{$this->input}%")
                    ->orWhere('city_id', 'like', "%{$this->input}%")
                    ->orWhere('district_id', 'like', "%{$this->input}%");
            }, '地址');
            $filter->like('first_party', '甲方名称');
            $filter->like('artisan_man', '技术负责人');
            $filter->like('price_man', '成本负责人');
            $filter->in('orientation', '项目定位')->checkbox(Project::GABC);
        });
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });
        $grid->disableExport();

        $grid->actions(function($action){
            $action->disableView();
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
        $show = new Show(Project::findOrFail($id));

        $show->id('ID');
        $show->name('项目名称');
        $show->addr('详细地址')->as(function($addr){
            return $this->province_id.$this->city_id.$this->district_id.$addr;
        });
        $show->first_party('甲方名称');
        $show->artisan_man('技术负责人');
        $show->price_man('成本负责人');
        $show->desc('项目简介');
        $show->orientation('项目定位')->as(function($c){
            return implode(',',$c);
        });
        $show->status('状态');

        return $show;
    }
    protected function detail_sm($id)
    {
        $show = new Show(Project::findOrFail($id));
        $show->desc('描述');
        $model=$show->getModel();
        $show->panel()
            ->title($model->name.'，'.$model->province_id.$model->city_id.$model->district_id.$model->addr)
            ->tools(function ($tools) {
                $tools->disableEdit();
                $tools->disableList();
                $tools->disableDelete();
            });;
        $show->elevators('已配备电梯', function ($grid) {
            $grid->resource('/admin/projectElevator');
            $grid->id('ID');
            $grid->eid('电梯设备')->display(function(){
                return 'ID:'.implode('|',json_decode(json_encode($this->device), true));
            });
            $grid->layer_number('层/站/门数');
            $grid->pit_depth('底坑深度mm');
            $grid->top_height('顶层高度mm');
            $grid->hall_width('厅门尺寸（mm）');
            $grid->car_width('轿厢尺寸（mm）');
            $grid->desc('电梯说明');
            $grid->column('num','数量')->editable('select', [1=>1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20]);
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

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Project);

        $form->text('name','项目名称');
        $form->distpicker(['province_id', 'city_id', 'district_id'], '省市区');
        $form->text('addr','详细地址');
        $form->text('first_party','甲方名称');
        $form->text('artisan_man','技术负责人');
        $form->text('price_man','成本负责人');
        $dsArr=[];
        foreach(Device::groupBy('brand')->get() as $d){
            $dsArr[$d->brand]=$d->brand;
        }
        $form->select('brand','电梯品牌')->options($dsArr)->required();
        $form->text('desc','项目简介');
        $form->checkbox('orientation','项目定位')->options(Project::GABC);


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
    public function elevator($pid, Content $content){
        $content
            ->header('项目管理')
            ->description('详情')
            ->body($this->detail_sm($pid));
        $content->row('<h3>请从下方选择电梯添加到项目</h3>');
        $content->row($this->eleGrid());
        return $content;
    }
    public function elevatorBind($pid,$eid,Content $content){
        $data=['pid'=>$pid,'eid'=>$eid];
        $pe=ProjectElevator::firstOrCreate($data);
        return $data;
    }

    function projectDetail($pid){
        $pj=Project::findOrFail($pid);
        $view=view('projectDetail',['pj'=>$pj]);
        return $view;
    }
}
