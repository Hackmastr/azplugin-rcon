<?php

namespace Azuriom\Plugin\Rcon\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\View\View;
use xPaw\SourceQuery\SourceQuery;

class RconController extends Controller
{
    /**
     * Show the home admin page of the plugin.
     *
     */
    public function index(): View
    {
        $servers = Server::all(['id', 'name']);
        return view('rcon::admin.index', [
            'servers' => $servers
        ]);
    }

    public function execute(Server $server, Request $request) : Response
    {
        if (!$server->bridge()->canExecuteCommand())
            return $this->error(__('rcon::admin.cantExecuteCommand'));

        if (!isset($server->data['rcon-password']))
            return $this->error(__('rcon::admin.invalidRconSettings'));

        $rconPassword = decrypt($server->data['rcon-password'], false);
        $port = $server->data['rcon-port'] ?? $server->port;

        $sq = new SourceQuery();

        try {
            $sq->Connect($server->address, $port);
            $sq->SetRconPassword($rconPassword);
        } catch (\Exception $e)
        {
            return $this->error(__('admin.servers.status.connect-error', [
                ':error' => $e->getMessage()
            ]));
        }

        $result = $sq->Rcon($request->get('cmd'));

        if ($result === false)
            return $this->error(__('rcon::admin.rconFailed'));

        return response($result);
    }

    private function error(string $msg) : Response
    {
        return response([
            'error' => $msg
        ], 400);
    }
}
