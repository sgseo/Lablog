<?php

namespace App\Models;


class Message extends Base
{
    /**
     * 添加数据
     *
     * @param  array $data 需要添加的数据
     * @return bool        是否成功
     */
    public function storeData($data)
    {
        //添加数据
        $result = $this
            ->create($data)
            ->id;
        if ($result) {
            show_message('留言成功，等待审核');
            return $result;
        } else {
            return false;
        }
    }
    // TODO：单体数据的审核以及多条数据的审核
    /**
     * 审核数据
     *
     * @param array $map
     * @return bool
     */
    public function checkData($map)
    {
        $model = $this
            ->whereMap($map)
            ->get();
        if ($model->isEmpty()) {
            show_message('非法数据！', false);
            return false;
        }
        foreach ($model as $k => $v) {
            $result = $v->forceFill(['status'=>1])->save();
        }
        if ($result) {
            show_message('操作成功');
            return $result;
        } else {
            return false;
        }
    }
    /**
     * 回复数据
     *
     * @param  int $id  id
     * @param  mix $reply 回复的数据
     * @return bool        是否成功
     */
    public function replyData($id, $reply)
    {
        $model = $this
            ->find($id);
        // 可能有查不到数据的情况
        if (!$model) {
            show_message('非法数据！', false);
            return false;
        }
        $result=$model->forceFill(['reply'=>$reply,'status'=>1])->save();;
        if ($result) {
            show_message('操作成功');
            return $result;
        } else {
            return false;
        }
    }
}
