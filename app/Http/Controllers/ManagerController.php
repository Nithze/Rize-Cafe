<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\User;
use App\Models\Meja;
use App\Models\Transaction;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function manager()
    {
        return view('manager.manager');
    }
    public function managerLog()
    {
        $users = User::all(); // Ambil semua data pengguna dari tabel users
        return view('manager.managerlog', compact('users')); // Kirim data pengguna ke view admin.blade.php
    }
    public function managertransactions()
    {
        $mejaAll = Meja::all();
        $user = Auth::user();
        $cashiers = User::where('usertype', 'cashier')->get();
        $transactions = Transaction::all();
        return view('manager.managertransactions', compact('transactions', 'mejaAll', 'cashiers'));

    }
    public function managerproduk()
    {
        $menus = Menu::all(); // Ambil semua data dari tabel Menu
        $meja = Meja::all(); // Ambil semua data dari tabel Menu

        return view('manager.managerproduk', compact('menus', 'meja')); // Kirim data menus ke tampilan
    }


    public function index()
    {
        // return view('admin.admin');
        $users = User::all(); // Ambil semua data pengguna dari tabel users
        return view('admin.admin', compact('users')); // Kirim data pengguna ke view admin.blade.php
    }


    public function store(Request $request)
    {

        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'usertype' => 'required|string|in:cashier,manager',
        ]);

        // Create a new user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'usertype' => $validated['usertype'],
        ]);

        return redirect()->back()->with('success', 'User added successfully');

    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully');
    }
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|min:4',
            'email' => 'required|email',
            'password' => 'nullable|min:8',
            'usertype' => 'required',
        ]);

        $user = User::find($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
        $user->usertype = $request->usertype;
        $user->save();

        return redirect()->back()->with('success', 'User updated successfully');
    }


    public function addMenu(Request $request)
    {
        // Validasi data yang diterima dari formulir
        $validatedData = $request->validate([
            'name' => 'required|min:4',
            'category' => 'required|in:Makanan,Minuman',
            'stock' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        // Simpan menu ke database
        try {
            $menu = new Menu;
            $menu->name = $validatedData['name'];
            $menu->category = $validatedData['category'];
            $menu->stock = $validatedData['stock'];
            $menu->price = $validatedData['price'];
            $menu->save();

            // Beri respons yang sesuai
            return redirect('manager/managerproduk')->with('success', 'Menu berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error adding menu: ' . $e->getMessage());

            // Return with error message
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan menu.');

        }
    }


    public function deleteMenu($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();

        return redirect()->back()->with('success', 'Menu berhasil dihapus');
    }
    // Controller method untuk memperbarui data menu
    public function getMenu($id)
    {
        $menu = Menu::find($id);
        return response()->json($menu);
    }

    public function editMenu(Request $request, $id)
    {
        $menu = Menu::find($id);
        $menu->name = $request->edit_name;
        $menu->category = $request->edit_category;
        $menu->stock = $request->edit_stock;
        $menu->price = $request->edit_price;
        $menu->save();

        return response()->json(['success' => true, 'message' => 'Menu berhasil diperbarui']);
        // return redirect('/manager/managerproduk')->with('success', 'Menu berhasil diperbarui');
    }
}
