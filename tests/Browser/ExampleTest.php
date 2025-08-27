<?php

it('allows users to login and see hello world', function () {
    visit(get_home_url().'/wp-login.php')
        ->type('#user_login', 'andrewrhyand')
        ->type('#user_pass', 'password')
        ->click('#wp-submit')
        ->navigate(get_home_url())
        ->assertSee('Howdy')
        ->assertSee('andrewrhyand');
});
