<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

defined('WUO_ROOT') or die('Доступ запрещён'); // Запрещаем прямой вызов скрипта.

// Массив $PARAMS получен в index.php
$page = (isset($PARAMS['page'])) ? $PARAMS['page'] : '';
$limit = (isset($PARAMS['rows'])) ? $PARAMS['rows'] : '';
$sidx = (isset($PARAMS['sidx'])) ? $PARAMS['sidx'] : '1';
$sord = (isset($PARAMS['sord'])) ? $PARAMS['sord'] : '';
$filters = (isset($PARAMS['filters'])) ? $PARAMS['filters'] : '';
$orgid = (isset($_POST['orgid'])) ? $_POST['orgid'] : '';
$oper = (isset($_POST['oper'])) ? $_POST['oper'] : '';
$id = (isset($_POST['id'])) ? $_POST['id'] : '';
$login = (isset($_POST['login'])) ? $_POST['login'] : '';
$pass = (isset($_POST['pass'])) ? $_POST['pass'] : '';
$email = (isset($_POST['email'])) ? $_POST['email'] : '';
$mode = (isset($_POST['mode'])) ? $_POST['mode'] : '';

if ($oper == '') {
	$flt = json_decode($filters, true);
	$cnt = count($flt['rules']);
	$where = '';
	for ($i = 0; $i < $cnt; $i++) {
		$field = $flt['rules'][$i]['field'];
		if ($field == 'org.id') {
			$field = 'org.name';
		}
		$data = $flt['rules'][$i]['data'];
		$where = $where."($field LIKE '%$data%')";
		if ($i < ($cnt - 1)) {
			$where = $where.' AND ';
		}
	}
	if ($where != '') {
		$where = 'WHERE '.$where;
	}
	$result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count, org.id AS orgid,"
			." users.id, users.orgid, users.login, users.password, users.email,"
			." users.mode, users.active, org.name AS orgname"
			." FROM users INNER JOIN org ON users.orgid=org.id ".$where);
	$row = mysqli_fetch_array($result);
	$count = $row['count'];
	$total_pages = ($count > 0) ? ceil($count / $limit) : 0;
	if ($page > $total_pages) {
		$page = $total_pages;
	}
	$start = $limit * $page - $limit;
	$SQL = "SELECT org.id AS orgid, users.id, users.orgid, users.login,"
			." users.password, users.email, users.mode, users.active,"
			." org.name AS orgname FROM users"
			." INNER JOIN org ON users.orgid=org.id ".$where." ORDER BY $sidx $sord LIMIT $start, $limit";
	$result = $sqlcn->ExecuteSQL($SQL)
			or die('Не могу выбрать список пользователей!'.mysqli_error($sqlcn->idsqlconnection));
	$responce = new stdClass();
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i = 0;
	while ($row = mysqli_fetch_array($result)) {
		$responce->rows[$i]['id'] = $row['id'];
		$mode = ($row['mode'] == '1') ? 'Да' : 'Нет';
		if ($row['active'] == '1') {
			$responce->rows[$i]['cell'] = array(
				'<img src="controller/client/themes/'.$cfg->theme.'/ico/accept.png">',
				$row['id'], $row['orgname'], $row['login'], 'скрыто', $row['email'], $mode
			);
		} else {
			$responce->rows[$i]['cell'] = array(
				'<img src="controller/client/themes/'.$cfg->theme.'/ico/cancel.png">',
				$row['id'], $row['orgname'], $row['login'], 'скрыто', $row['email'], $mode
			);
		}
		$i++;
	}
	header('Content-type: application/json');
	echo json_encode($responce);
}

if ($oper == 'edit') {
	$imode = ($mode == 'Да') ? 1 : 0;
	$ps = ($pass != 'скрыто') ? "`password`=SHA1(CONCAT(SHA1('$pass'), salt))," : '';
	$SQL = "UPDATE users SET mode='$imode', login='$login',$ps email='$email' WHERE id='$id'";
	$sqlcn->ExecuteSQL($SQL)
			or die('Не могу обновить данные по пользователю!'.mysqli_error($sqlcn->idsqlconnection));
}

if ($oper == 'add') {
	$SQL = "INSERT INTO knt (id, name, comment, active) VALUES (null, '$name', '$comment', 1)";
	$sqlcn->ExecuteSQL($SQL)
			or die('Не могу добавить пользователя!'.mysqli_error($sqlcn->idsqlconnection));
}

if ($oper == 'del') {
	$SQL = "UPDATE users SET active=not active WHERE id='$id'";
	$sqlcn->ExecuteSQL($SQL)
			or die('Не могу обновить данные по пользователю!'.mysqli_error($sqlcn->idsqlconnection));
}
