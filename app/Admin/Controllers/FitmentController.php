<?php

namespace App\Admin\Controllers;

use App\Models\Device;
use App\Models\Fitment;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class FitmentController extends Controller
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
            ->header('电梯装修价')
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
            ->header('电梯装修价')
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
            ->header('电梯装修价')
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
            ->header('电梯装修价')
            ->description('新建')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Fitment);

        $grid->id('ID');
        $grid->brand('品牌')->display(function ($brand) {
            $html='';
            foreach($brand as $dl){
                $html.="<div class='label label-default'>$dl</div> ";
            }
            return $html;
        });
        $grid->name('装饰项目名称');
        $grid->stuff('材料');
        $grid->spec('规格编号');
        $grid->price('装修单价')->display(function($price){
            return number_format($price,2).' 元/'.$this->unit;
        });
        $grid->desc('描述');
        $grid->has_in_base('是否标配/是否在基础价格包含')->display(function($has_in_base){
            return $has_in_base==1?'已包含':'未包含';
        });
        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            // 在这里添加字段过滤器
            $filter->like('brand', '品牌');
            $filter->like('brand_set', '品牌系列');
            $filter->like('dload', '载重');
            $filter->where(function ($query) {
                $query->whereRaw("find_in_set('{$this->input}',`floor`)");
            }, '楼层');
            $filter->like('from', '发货地点');
            $filter->like('to', '到货地点');
        });
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });
        $grid->disableExport();
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableView();
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
        $show = new Show(Fitment::findOrFail($id));

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
    protected function form()
    {
        $form = new Form(new Fitment);

        $dsArr=[];
        foreach(Device::groupBy('brand')->get() as $d){
            $dsArr[$d->brand]=$d->brand;
        }
        $form->multipleSelect('brand','品牌')->options($dsArr)->required();

        $form->text('name','装饰项目名称')->required();
        $form->text('stuff','材料');
        $form->text('spec','规格编号');
        $form->radio('unit','装修单位')->options(Fitment::UNIT)->required();
        $form->currency('price','单价')->symbol('￥');
        $states = [
            'on'  => ['value' => 1, 'text' => '包含', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '不包含', 'color' => 'danger'],
        ];
        $form->switch('has_in_base','是否标配/是否在基础价格包含')->states($states);
        $form->text('desc','描述');

        //忽略字段
        $form->ignore([]);

        $form->saving(function (Form $form){
            $form->brand=implode(',',$form->brand);
        });
        return $form;
    }
}
