<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\EmailVerificationRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    private $userRepository;
    private $emailVerificationRepository;

    public function __construct(UserRepository $userRepository, EmailVerificationRepository $emailVerificationRepository)
    {
        $this->userRepository = $userRepository;
        $this->emailVerificationRepository = $emailVerificationRepository;
    }

    public function loadRegister()
    {
        return view('register');
    }

    public function studentRegister(Request $request)
    {
        $request->validate([
            'name' => 'string|required|min:2',
            'email' => 'string|email|required|max:100|unique:users',
            'password' => 'string|required|confirmed|min:6'
        ]);

        $user = $this->userRepository->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return redirect("/verification/" . $user->id);
        // return response()->json('OTP sent Successfully For ID:'.$user->id. 'Please Check Your Email');
    }

    public function loadLogin()
    {
        if (Auth::user()) {
            return redirect('/dashboard');
        } else {
            return view('login');
        }
    }

    public function sendOtp($user)
    {
        $otp = rand(100000, 999999);
        $time = time();

        $this->emailVerificationRepository->updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'otp' => $otp,
                'created_at' => $time
            ]
        );

        $data['email'] = $user->email;
        $data['title'] = 'Mail Verification';
        $data['body'] = 'Your OTP is: ' . $otp;

        Mail::send('mailVerification', ['data' => $data], function ($message) use ($data) {
            $message->to($data['email'])->subject($data['title']);
        });
    }

    public function userLogin(Request $request)
    {
        $request->validate([
            'email' => 'string|required|email',
            'password' => 'string|required'
        ]);

        $userCredential = $request->only('email', 'password');
        $userData = $this->userRepository->findByEmail($request->email);

        if ($userData && $userData->is_verified == 0) {
            $this->sendOtp($userData);
            return redirect("/verification/" . $userData->id);
        } else if (Auth::attempt($userCredential)) {
            // return redirect('/dashboard');
            return response()->json('You are logged in');
        } else {
            // return back()->with('error', 'Username & Password is incorrect');
            return response()->json('Username OR Password is incorrect');
        }
    }

    public function loadDashboard()
    {
        if (Auth::user()) {
            return view('dashboard');
        } else {
            return redirect('/');
        }
    }

    public function verification($id)
    {
        $user = $this->userRepository->findById($id);

        if (!$user || $user->is_verified == 1) {
            return redirect('/');
        }

        $email = $user->email;

        $this->sendOtp($user);

        // return view('verification', compact('email'));
        return response()->json('OTP sent Successfully For ID:' . $user->id . 'Please Check Your Email');
    }

    public function verifiedOtp(Request $request)
    {
        $user = $this->userRepository->findByEmail($request->email);
        $otpData = $this->emailVerificationRepository->findByOtp($request->otp);

        if (!$otpData) {
            return response()->json(['success' => false, 'msg' => 'You entered the wrong OTP']);
        } else {
            $currentTime = time();
            $time = $otpData->created_at;

            if ($currentTime >= $time && $time >= $currentTime - (90 + 5)) {
                $this->userRepository->update($user->id, ['is_verified' => 1]);

                return response()->json(['success' => true, 'msg' => 'Mail has been verified']);
            } else {
                return response()->json(['success' => false, 'msg' => 'Your OTP has expired']);
            }
        }
    }

    public function resendOtp(Request $request)
    {
        $user = $this->userRepository->findByEmail($request->email);
        $otpData = $this->emailVerificationRepository->findByEmail($request->email);

        $currentTime = time();
        $time = $otpData->created_at;

        if ($currentTime >= $time && $time >= $currentTime - (90 + 5)) {
            return response()->json(['success' => false, 'msg' => 'Please try again after some time']);
        } else {
            $this->sendOtp($user);

            return response()->json(['success' => true, 'msg' => 'OTP has been sent']);
        }
    }
}
