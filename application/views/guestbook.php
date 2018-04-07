<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 13.08.17
 * Time: 14:35
 */
defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Гостевая книга</title>
    <?php echo link_tag('css/bootstrap.min.css'); ?>
</head>
<body>
<div id="login-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Заголовок модального окна -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Вход</h4>
            </div>
            <!-- Основное содержимое модального окна -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label for="username" class="col-sm-2 control-label">Имя</label>
                        <div class="col-sm-10">
                        <input id="username" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-sm-2 control-label">Пароль</label>
                        <div class="col-sm-10">
                            <input type="password" id="password" class="form-control">
                        </div>
                    </div>
                    <div class="text-right form-group">
                        <div class="col-sm-12">
                        <button class="btn btn-default" id="login">Войти</button>
                        </div>
                    </div>
                </form>
                    </div>
                </div>
            </div>
            <!-- Футер модального окна -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="text-right">
                <?php if ($this->session->logged_in == TRUE): ?>
                    <?php echo '<a href="'.base_url().'gb/logout">Выйти</a>' ;?>
                <?php else: ?>
                    <?php echo '<a href="#" data-toggle="modal" data-target="#login-modal">Войти</a>'; ?>
                <?php endif; ?>
            </div>
            <?php echo heading('Гостевая книга', 1, array('class' => 'text-center')); ?>
        </div>
        <div class="col-sm-8 col-sm-offset-2">
            <div id="posts">
                <?php foreach ($posts as $msg): ?>
                    <div class="comment">
                    <div class="row">
                        <h4 class="col-sm-6"><a href="mailto:<?php echo $msg['email']; ?>"><?php echo $msg['name']; ?>
                            </a>
                            <?php if ($this->session->logged_in == TRUE): ?>
                            <?php echo '&nbsp;ID: '.$msg['id']; ?>
                            <?php endif; ?>
                        </h4><h4 class="text-right col-sm-6">
                            <?php echo date('d.m.Y H:i:s', strtotime($msg['datetime'])); ?>
                            <?php if ($this->session->logged_in == TRUE): ?>
                            <?php echo '<a href="#" class="delete" id="msg-'.$msg['id'].'">
<span class="glyphicon glyphicon-trash" data-toggle="tooltip" data-placement="top" title="Удалить"></span>
</a>'; ?>
                            <?php endif; ?>
                        </h4>
                    </div>
                    <div class="well"><?php echo $msg['message']; ?></div>
                    </div>
                <?php endforeach; ?>
                <?php echo $this->pagination->create_links(); ?>
            </div>
            <form>
                <div class="form-inline form-group">
                <div class="form-group">
                    <label for="name" class="control-label"><?php echo lang('name'); ?></label>
                    <input id="name" class="form-control">
                </div>
                    &nbsp;
                <div class="form-group">
                        <label for="email" class="control-label"><?php echo lang('email'); ?></label>
                        <input type="email" id="email" class="form-control">
                </div>
                </div>
                <div class="form-group">
                <label for="message" class="control-label"><?php echo lang('message'); ?></label>
                <textarea class="form-control" id="message" rows="5"></textarea>
                </div>
                <div class="text-right form-group">
                    <button class="btn btn-default" id="send">Отправить</button>
                </div>
            </form>
        </div>
    </div>
</div>
     <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
     <!-- Include all compiled plugins (below), or include individual files as needed -->
<script type="text/javascript">
    var base_url = '<?php echo base_url(); ?>';
</script>
<script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>js/script.js"></script>
</body>
</html>
