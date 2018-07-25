<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\User\SkbUsersModel;
use App\Traits\{Tool, Session};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SkbSuggestions extends Controller
{
    /**
     * 提交意见反馈
     * @param Request $req
     * @return $this
     */
    public function submitSuggestion(Session $ssn, Request $req)
    {
        $this->validate($req, [
            'feedback_content' => 'required',
        ]);

        $content = $req->get('feedback_content');
        $uid     = $ssn->get('user.id');

        if ($uid) {
            $user     = new SkbUsersModel;
            $userInfo = $user->select('id')
                        ->find($uid)
                        ->first();
        }

        $dat = DB::table('skb_suggestions')->insert([
            'feedback_name'    => $userInfo['username'],
            'feedback_mobile'  => $userInfo['mobile'],
            'feedback_content' => $content,
            'created_at'       => date('Y-m-d H:i:s', time()),
            'updated_at'       => date('Y-m-d H:i:s', time()),
        ]);

        $err = $dat ? 0 : 404;
        $msg = $dat ? 'success' : 'fails';

        return Tool::jsonResp([
            'err' => $err,
            'msg' => $msg,
            'dat' => $dat,
        ]);
    }

    /**
     * 提交意见反馈, 只存储,无任何操作
     * @param Session $ssn
     * @param Request $req
     * @return $this
     */
    public function submitFeedback(Session $ssn, Request $req)
    {
        $this->validate($req, [
            'feedback_content' => 'required',
        ]);

        $content = $req->get('feedback_content');
        $uid     = $ssn->get('user.id');

        $dat = DB::table('skb_feedback')->insert([
            'uid'              => $uid,
            'feedback_content' => $content,
            'created_at'       => date('Y-m-d H:i:s', time()),
            'updated_at'       => date('Y-m-d H:i:s', time()),
        ]);

        $err = $dat ? 0 : 404;
        $msg = $dat ? 'success' : 'fails';

        return Tool::jsonResp([
            'err' => $err,
            'msg' => $msg,
            'dat' => $dat,
        ]);
    }

    public function submitFeedbackUser(Session $ssn, Request $req)
    {
        $this->validate($req, [
            'feedback_cate'    => 'required|numeric',
            'feedback_type'    => 'required|numeric',
            'feedback_content' => 'required',
            'contacts'         => 'required',
            'contacts_info'    => 'required',
        ]);

        $params = $req->all();
        $params['uid'] = $ssn->get('user.id');

        $dat = DB::table('skb_feedback_user')->insert([
            'uid'              => $params['uid'],
            'feedback_cate'    => $params['feedback_cate'],
            'feedback_type'    => $params['feedback_type'],
            'feedback_content' => $params['feedback_content'],
            'feedback_img'     => $params['feedback_img'] ?? '',
            'contacts'         => $params['contacts'],
            'contacts_info'    => $params['contacts_info'],
            'created_at'       => date('Y-m-d H:i:s', time()),
            'updated_at'       => date('Y-m-d H:i:s', time()),
        ]);

        $err = $dat ? 0 : 404;
        $msg = $dat ? 'success' : 'fails';

        return Tool::jsonResp([
            'err' => $err,
            'msg' => $msg,
            'dat' => $dat,
        ]);
    }
}