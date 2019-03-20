<?php

namespace App\Admin\Controllers;

use App\Models\Elevator;
use App\Models\Project;
use App\Http\Controllers\Controller;
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
        return $content
            ->header('项目管理')
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
$js=<<<END
<script>
$(function(){
    $('.table td').on('click','.icheckbox_minimal-blue',function(){
        alert(1);
    })
})
</script>
END;
        $content->body($js);
        $content->row(function(Row $row){
            $row->column(4, $this->form());
            $row->column(8, $this->eleGrid());
        });
        return $content;
    }
    private function eleGrid(){
        $grid = new Grid(new Elevator);
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
        $show->created_at('Created at');
        $show->updated_at('Updated at');

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
        $form->text('desc','项目简介');
        $form->checkbox('orientation','项目定位')->options(Project::GABC);

        return $form;
    }
}
