<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{

public function login(){
    $data['title'] = 'Login';
    return view('login', $data);
}
public function dashboard(){
    $data['title'] = 'dashboard';
    return view('dashboard.index', $data);
}
public function userRegistration(){
    $data['title'] = 'User Registration';
    return view('users.index', $data);
}
public function userActivity(){
    $data['title'] = 'User Activity';
    return view('users.activity', $data);
}
public function moderationDashboard(){
    $data['title'] = 'Moderation Dashboard';
    return view('moderation.dashboard', $data);
}
// public function moderationQuestions(){
//     $data['title'] = 'Moderation Questions';
//     return view('moderation.questions', $data);
// }
// public function moderationContents(){
//     $data['title'] = 'Moderation Contents';
//     return view('moderation.contents', $data);
// }
// public function moderationComments(){
//     $data['title'] = 'Moderation Comments';
//     return view('moderation.comments', $data);
// }
public function manageContent(){
    $data['title'] = 'Manage Contents';
    return view('content.manage', $data);
}
public function subjects(){
    $data['title'] = 'Manage Subjects';
    return view('subjects.index', $data);
}
public function support(){
    $data['title'] = 'Support';
    return view('support.index', $data);
}

public function announcement(){
    $data['title'] = 'Platform Announcements';
    return view('platform.announcements', $data);
}
public function role(){
    $data['title'] = 'Platform Roles';
    return view('platform.roles', $data);
}
}

