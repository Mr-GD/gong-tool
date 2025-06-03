<?php

namespace gong\tool\base\api\Excel;

interface Paging
{
    public function paging(int $page, int $limit = 10000);
}