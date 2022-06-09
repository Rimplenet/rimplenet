<?php
 $instance = new RimplenetGetUser();

 $users = $instance->get_users();

 echo json_encode($users['data']);