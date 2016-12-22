function submit() {
    $('button').addClass('disabled');
    $('button').html('处理中');
    $.post('index.php?func=login', {
        'userid': $('#userid').val(),
        'passwd': $('#passwd').val(),
        'checkcode': $('#checkcode').val()
    }, function (rs) {
        $('button').html('评估');
        $('button').removeClass('disabled');
        // alert(rs.indexOf('评估')!=-1);
        if(rs.indexOf('评估')==-1)
            refresh();
        showRs(rs);
    })
}

function checkVal() {
    clearRs();
    if($('#userid').val()=='') {showRs('请输入账号');return;}
    if($('#passwd').val()=='') {showRs('请输入密码');return;}
    if($('#checkcode').val()=='') {showRs('请输入验证码');return;}
    submit();
}
function refresh() {
    clearRs();
    $('img').attr('onclick', '');
    $('img').attr('src','');
    $.get("./index.php?func=getcs", {}, function (rs) {
        $('img').attr('src',rs);
        $('img').attr('onclick', 'refresh()');
    })
}
function showRs(msg) {
    $('.text-info').html(msg);
}
function clearRs() {
    $('.text-info').html('');
}
