<?php
/**
 * Created by PhpStorm.
 * User: goto9
 * Date: 2019/3/21
 * Time: 18:02
 */
namespace App\Exceptions;
use App\Models\Elevator;
use Encore\Admin\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Grid\Column;
use Encore\Admin\Grid\Displayers\AbstractDisplayer;
class ProjectStatus extends AbstractDisplayer
{
    /**
     * {@inheritdoc}
     */
    public function display()
    {
        $status=$this->value;
        $statusStr=Elevator::getStatusStr($this->value);
        //dump($this);
        $script = <<<SCRIPT
$('.paylog-refund').unbind('click').click(function() {
    var id = $(this).data('id');
    var type = $(this).data('type');
    var ajax=true;
    swal({
          title: "请确认是否提交？",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "提 审",
          cancelButtonText: "取 消"
        }).then(function(isConfirm){
            console.log(ajax,isConfirm)
            if(!isConfirm.value || !ajax) return;
            ajax=false;
            $.ajax({
                method: 'post',
                url: '{$this->getResource()}/' + id+'?act=status',
                data: {
                    _method:'get',
                    _token:LA.token,
                },
                success: function (data) {
                    $.pjax.reload('#pjax-container');
                    swal('操作成功', '', 'success');
                },
                error: function(x, e) {
                    if (x.status == 500) {
                        swal(x.responseJSON.message, '', 'error');
                    }
                },
            });
        });
});
SCRIPT;
        Admin::script($script);
        if($status==0){
$html=<<<EOT
<button type="button"
    class="btn btn-danger paylog-refund"
    title="提交审核" data-id="{$this->getKey()}">
    {$statusStr}
</button>
EOT;
        }else{
            $html="<span class=\"label label-default\">$statusStr</span>";
        }
        return $html;
    }
}
