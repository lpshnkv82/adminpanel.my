<div class="panel_users">
    <div class="add_new_users"><a href="#">Добавить нового пользователя</a></div>
    <div class="users_head"><h2>Пользователи</h2>
<!--        <a class="add_new_group" href="#">Добавить новую группу пользователей</a>-->
    </div>

    <div class="users_container">
        <div class="new_user_container">
            <form class="users_item" method="post" action="<?=PATH.ADMIN_PATH?>users">
                <span><label for="Login">Логин:</label><input type="text" name="login" value="" onfocus="this.removeAttribute('readonly')" readonly></span>
                <span><label for="password">Пароль:</label><input type="password" name="password" onfocus="this.removeAttribute('readonly')" readonly></span>
							<span><label for="groupUsers">Группа пользователей:</label>
                                <?php if($usersTypes):?>
                                    <select name="user_type">
                                        <?php foreach($usersTypes as $type):?>
                                            <option value="<?=$type['id']?>"><?=$type['user_type']?></option>
                                        <?php endforeach;?>
                                    </select>
                                <?php endif;?>
							</span>
							<span class="usersChange">
								<input type="submit" name="addUser" value="Добавить">
							</span>
            </form>
        </div>

        <?php if($users):?>
            <?php foreach($users as $user):?>
                <form class="users_item" method="post" action="<?=PATH.ADMIN_PATH?>users">
                    <input type="hidden" name="user_id" value="<?=$user['user_id']?>">
                    <span><label for="Login">Логин:</label><input type="text" name="login" value="<?=$user['login']?>" onfocus="this.removeAttribute('readonly')" readonly></span>
                    <span><label for="password">Пароль:</label><input type="password" name="password" onfocus="this.removeAttribute('readonly')" readonly></span>
						<span><label for="groupUsers">Группа пользователей:</label>
                            <?php if($usersTypes):?>
                                <select name="user_type">
                                    <?php foreach($usersTypes as $type):?>
                                        <option value="<?=$type['id']?>" <?php if($type['id'] == $user['user_type']) echo 'selected';?>><?=$type['user_type']?></option>
                                    <?php endforeach;?>
                                </select>
                            <?php endif;?>
						</span>
						<span class="usersChange">
							<input type="submit" name="change" value="Изменить">
							<a class="delete_user vg_delete" href="<?=PATH.ADMIN_PATH?>/users/delete/<?=$user['user_id']?>">Удалить</a>
						</span>
                </form>
            <?php endforeach;?>
        <?php endif;?>

    </div>
</div>
<div class="modal_overlay"></div>
<div class="modal_users">
    <a href="#" id="modal_users_ext">✖	</a>
    <form class="users_item" method="post" action="<?=PATH?>users">
        <span><label for="addGroupName"> Имя группы:</label><input type="text" name="user_type"></span>
			<span class="usersChange">
				<input type="submit" name="addGroup" value="Добавить"></span>
    </form>
</div>