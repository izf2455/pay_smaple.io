<?php
$mod='blank';
include("../includes/common.php");
$title='管理账号配置';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
if(isset($_POST['submit'])) {
    foreach ($_POST as $x => $value) {
        if($x=='pwd')continue;
        $value=daddslashes($value);
        $DB->query("insert into opao_config set `x`='{$x}',`j`='{$value}' on duplicate key update `j`='{$value}'");
    }
    $pwd=daddslashes($_POST['pwd']);
    if(!empty($pwd))$DB->query("update `opao_config` set `j` ='{$pwd}' where `x`='admin_pwd'");
    echo "<script language='javascript'>alert('报告老大，您的管理信息已修改成功！');window.location.href='./op_adminset.php';</script>";
    exit();
}
?>
<!-- 天方夜谭易支付系统：www.pxula.cn -->
    <div class="header bg-<?php echo $conf['adminmb_ys']?> pb-6"><div class="container-fluid"><div class="header-body"><div class="row align-items-center py-4"><div class="col-lg-6 col-7"><h6 class="h2 text-white d-inline-block mb-0">管理账号配置</h6><nav aria-label="breadcrumb"class="d-none d-md-inline-block ml-md-4"><ol class="breadcrumb breadcrumb-links breadcrumb-dark"><li class="breadcrumb-item"><a href="#!"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#!">其他配置</a></li><li class="breadcrumb-item active" aria-current="page">管理账号配置</li></ol></nav></div></div></div></div>
    </div>
    <div class="container-fluid mt--6">
      <div class="row">
        <div class="col">
          <div class="card-wrapper">
            <!-- Custom form validation -->
            <div class="card">
              <!-- Card header -->
              <div class="card-header">
                <h3 class="mb-0">管理账号配置</h3>
              </div>
              <!-- Card body -->
              <div class="card-body">
                <div class="row"><div class="col-lg-8"><p class="mb-0"><code>温馨提示：</code>请不要被人知道您的管理员密码哦，建议您养成常修改密码的习惯~</p></div>
                </div><hr>
                <form class="needs-validation"action="./op_adminset.php?mod=site_n"method="post"role="form"><div class="form-row"><div class="col-md-12 mb-3"><label class="form-control-label">您的管理员登录账号:</label><input type="text"name="admin_user"class="form-control"value="<?php echo $conf['admin_user']; ?>"required></div></div><div class="form-row"><div class="col-md-12 mb-3"><label class="form-control-label">您的管理员登录密码:</label><input type="password"name="admin_pwd"class="form-control"value="<?php echo $conf['admin_pwd']; ?>"required></div></div><div class="form-group"><div class="custom-control custom-checkbox mb-3"><input class="custom-control-input"id="invalidCheck"type="checkbox"required=""><label class="custom-control-label"for="invalidCheck">我已确保为本人修改信息</label></div></div><button class="btn btn-<?php echo $conf['adminmb_ys']?> form-control"type="submit"name="submit">确定修改</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- 主页结束 -->
<?php include 'foot.php';?>
<script>var items=$("select[default]");for(i=0;i<items.length;i++){$(items[i]).val($(items[i]).attr("default"))}</script>