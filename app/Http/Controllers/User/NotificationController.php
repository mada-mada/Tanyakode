<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
  public function index()
  {
    $notifications = DB::table('notifications')
      ->where('notifiable_id', Auth::id())
      ->orderBy('created_at', 'desc')
      ->paginate(15);

    return view('user.notifications.index', compact('notifications'));
  }

  public function unreadCount()
  {
    if (!Auth::check()) {
      return response()->json(['count' => 0]);
    }

    $count = DB::table('notifications')
      ->where('notifiable_id', Auth::id())
      ->whereNull('read_at')
      ->count();

    return response()->json(['count' => $count]);
  }

  public function markAllRead()
  {
    if (!Auth::check()) {
      return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
    }

    DB::table('notifications')
      ->where('notifiable_id', Auth::id())
      ->whereNull('read_at')
      ->update(['read_at' => now()]);

    return response()->json(['status' => 'success', 'message' => 'Semua notifikasi ditandai sebagai dibaca']);
  }
}
