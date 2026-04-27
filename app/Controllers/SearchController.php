<?php 

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\SearchService;
use Core\Request;
use Core\Response;
use Core\View;

class SearchController {
    public function searchHandler(): void {
        if (!AuthService::check()) {
            (new Response())->redirect('/login');
            return;
        }

        $request = new Request();
        $query = trim($request->get('q') ?? '');

        $results = [];

        if ($query !== '') {
            $searchService = new SearchService();
            $results = $searchService->searchParam($query);
        }

        View::render('search/index', ['results' => $results]);
    }

    public function suggestHandler(): void {
        if (!AuthService::check()) {
            http_response_code(401);
            echo json_encode([]);
            return;
        }

        $request = new Request();
        $query = trim($request->get('q') ?? '');

        if ($query === '') {
            echo json_encode([]);
            return;
        }

        $searchService = new SearchService();
        $results = $searchService->searchParam($query);

        header('Content-Type: application/json');
        echo json_encode($results);
    }
}
