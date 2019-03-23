<style>
    .project {
        background-color: #fefefe;;
    }
    .project address{
        border: 1px solid #ccc;
    }
</style>
<div class="project" id="project">
    <h1 class="text-center">{{$pj->name}}</h1>
    <div class="project container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <address>
                    <strong>项目地点</strong><br>
                    {{$pj->province_id}}/{{$pj->city_id}}/{{$pj->district_id}} {{$pj->addr}}
                </address>
            </div>
            <div class="col-xs-12 col-sm-4">
                <address>
                    <strong>甲方名称</strong><br>
                    {{$pj->first_party}}
                </address>
            </div>
            <div class="col-xs-12 col-sm-4">
                <address>
                    <strong>技术负责人</strong><br>
                    {{$pj->artisan_man}}
                </address>
            </div>
            <div class="col-xs-12 col-sm-4">
                <address>
                    <strong>成本负责人</strong><br>
                    {{$pj->price_man}}
                </address>
            </div>
            <div class="col-xs-12 col-sm-4">
                <address>
                    <strong>项目定位</strong><br>
                    {{$pj->desc}}
                </address>
            </div>
            <div class="col-xs-12 col-sm-4">
                <address>
                    <strong>项目状态</strong><br>
                    {{$pj->status}}
                </address>
            </div>
        </div>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>No.</th>
                <th>数量</th>
                <th>提升高度</th>
                <th>层/站/门数</th>
                <th>停站标记</th>
                <th>基站楼层</th>
                <th>标准</th>
                <th>驱动方式</th>
                <th>控制方式</th>
                <th>消防返回开关在</th>
                <th>暂停服务开关在</th>
                <th>控制功能</th>
                <th>底坑深度mm</th>
                <th>顶层高度mm</th>
                <th>井道结构</th>
                <th>井道支架固定</th>
                <th>厅门尺寸（mm）</th>
                <th>轿厢尺寸（mm）</th>
                <th>备注</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($pj->elevator as $ele):?>
            <tr>
                <td>No.{{$ele->id}}</td>
                <td>{{$ele->num}}部</td>
                <td>{{$ele->height}}</td>
                <td>{{$ele->layer_number}}</td>
                <td>{{$ele->stop_sign}}</td>
                <td>{{$ele->base_floor}}</td>
                <td>{{$ele->standard}}</td>
                <td>{{$ele->drive_mode}}</td>
                <td>{{$ele->control_mode}}</td>
                <td>{{$ele->fire_switch}}</td>
                <td>{{$ele->stop_switch}}</td>
                <td>{{$ele->control_function}}</td>
                <td>{{$ele->pit_depth}}</td>
                <td>{{$ele->top_height}}</td>
                <td>{{$ele->well_structure}}</td>
                <td>{{$ele->well_fixation}}</td>
                <td>{{$ele->hall_width}}*{{$ele->hall_height}}</td>
                <td>{{$ele->car_width}}*{{$ele->car_height}}*{{$ele->car_depth}}</td>
                <td>{{$ele->desc}}</td>
            </tr>

            <!--//电梯设备价START-->
            <tr>
                <td></td>
                <td>设备价</td>
                <td colspan="100">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>品牌</th>
                                <th>载重</th>
                                <th>速度</th>
                                <th>楼层</th>
                                <th>标准提升高度</th>
                                <th>品牌系列</th>
                                <th>设备超米费</th>
                                <th>安装超米费</th>
                                <th>设备税率</th>
                                <th>安装单价</th>
                                <th>设备单价</th>
                                <th>安装税率</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $device=$ele->deviceAll;?>
                            <tr>
                                <td>{{$device->id}}</td>
                                <td>{{$device->brand}}</td>
                                <td>{{$device->dload}}</td>
                                <td>{{$device->speedup}}</td>
                                <td>{{$device->floor}}</td>
                                <td>{{$device->hoisting_height}}</td>
                                <td>{{$device->brand_set}}</td>
                                <td>{{$device->freeboard_dev}}</td>
                                <td>{{$device->freeboard_ins}}</td>
                                <td>{{$device->device_rate}}</td>
                                <td>{{$device->install_price}}</td>
                                <td>{{$device->device_price}}</td>
                                <td>{{$device->install_rate}}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <!--//电梯设备价END-->

            <!--//电梯功能价START-->
            <tr>
                <td></td>
                <td>功能价</td>
                <td colspan="100">
                    <table class="table table-bordered table-condensed">
                        <thead>
                        <tr>
                            <th>功能ID</th>
                            <th>功能名称</th>
                            <th>功能加价</th>
                            <th>是否标配/是否在基础价格包含</th>
                            <th>功能描述</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($ele->func as $func):?>
                        <tr>
                            <td>{{$func->id}}</td>
                            <td>{{$func->name}}</td>
                            <td>{{$func->price}} 元/{{$func->unit}}</td>
                            <td>{{$func->has_in_base}}</td>
                            <td>{{$func->desc}}</td>
                        </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>
                </td>
            </tr>
            <!--//电梯功能价END-->

            <!--//电梯装修价START-->
            <tr>
                <td></td>
                <td>装修价</td>
                <td colspan="100">
                    <table class="table table-bordered table-condensed">
                        <thead>
                        <tr>
                            <th>装修ID</th>
                            <th>装修名称</th>
                            <th>装修材料</th>
                            <th>规格编号</th>
                            <th>装修加价</th>
                            <th>是否标配/是否在基础价格包含</th>
                            <th>装修描述</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($ele->fitment as $fitment):?>
                        <tr>
                            <td>{{$fitment->id}}</td>
                            <td>{{$fitment->name}}</td>
                            <td>{{$fitment->stuff}}</td>
                            <td>{{$fitment->spec}}</td>
                            <td>{{$fitment->price}} 元/{{$fitment->unit}}</td>
                            <td>{{$fitment->has_in_base}}</td>
                            <td>{{$fitment->desc}}</td>
                        </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>
                </td>
            </tr>
            <!--//电梯装修价END-->

            <!--//电梯运费价START-->
            <?php
            $freight=\App\Models\DeviceFreight::where([
                'did'=>$ele->did,
                'to_province'=>$pj->province_id,
                'to_city'=>$pj->city_id,
            ])->first();
            ?>
            <tr>
                <td></td>
                <td>运费价</td>
                <td colspan="100">
                    <?php if($freight):?>
                    <div class="row">
                        <div class="col-xs-12 col-sm-4">
                            <address>
                                <strong>发货地</strong><br>
                                {{$freight->from_province}}/{{$freight->from_city}}
                            </address>
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <address>
                                <strong>收获地</strong><br>
                                {{$freight->to_province}}/{{$freight->to_city}}
                            </address>
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <address>
                                <strong>运费单价</strong><br>
                                {{$freight->price}}
                            </address>
                        </div>
                    </div>
                    <?php else:?>
                        <address>缺少运费价</address>
                    <?php endif;?>
                </td>
            </tr>
            <!--//电梯运费价END-->

            <?php endforeach;?>
        </tbody>
    </table>
</div>