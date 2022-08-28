<?php

interface JobContract
{
    public function handler(array $data);
}