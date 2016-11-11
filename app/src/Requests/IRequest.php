<?php
namespace App\Requests;

interface IRequest
{
    /**
     * @return array
     */
    public function rules();
}