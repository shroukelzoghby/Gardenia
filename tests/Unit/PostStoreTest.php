<?php

it('can create a post', function () {
    $response=$this->user('/create_post');
    $response->assertStatus(302);
});
