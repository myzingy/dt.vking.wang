<?php
/**
 * Created by PhpStorm.
 * User: goto9
 * Date: 2019/3/21
 * Time: 18:02
 */
namespace App\Exceptions;
use Encore\Admin\Admin;
use Encore\Admin\Grid\Displayers\AbstractDisplayer;
class Fitout extends AbstractDisplayer
{
    /**
     * {@inheritdoc}
     */
    public function display()
    {
        $script = <<<SCRIPT
$('.paylog-refund').unbind('click').click(function() {
    var id = $(this).data('id');
    var ajax=true;
    swal({
          title: "配备到项目",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "配 备",
          cancelButtonText: "取 消"
        }).then(function(isConfirm){
            console.log(ajax,isConfirm)
            if(!isConfirm.value || !ajax) return;
            ajax=false;
            $.ajax({
                method: 'post',
                url: '{$this->getResource()}/' + id+'/edit?act=refund',
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
        return <<<EOT
<button type="button"
    class="btn btn-danger paylog-refund"
    title="点击配备到项目" data-id="{$this->getKey()}">
    配备
</button>
EOT;
    }
}
