<?php

namespace Backscreen\Clients\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Backscreen\Clients\Models\Client;
use Backscreen\Clients\Http\Requests\StoreClientRequest;
use Backscreen\Clients\Http\Resources\ClientResource;
use Backscreen\Helpers\Logger;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Exception;


/**
 * Note: For larger and more complex functionality, I would recommended to
 * implement the Service-Repository pattern. This pattern separates
 * the business logic into service classes and database interactions into 
 * repositories. This approach improves maintainability, testability,
 * and scalability of the application, especially when multiple data sources
 * or complex operations are involved.
 * 
 * Example:
 * 
 * - ClientRepository: handles data access operations (find, create, update, delete)
 * - ClientService: contains business logic and interacts with the repository
 * 
 * This way, the controller remains thin and only handles request validation and responses.
 * 
 */
class ClientsController extends Controller
{
    
    protected $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Display a listing of the clients.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            // Retrieve all clients from the database
            $clients = Client::all();

            return ClientResource::collection($clients);
        } catch (Exception $e) {
            // Log any exception that occurs for further inspection
            $this->logger->log('error', $e->getMessage());

            // Return a JSON response indicating a server error
            return response()->json(['error' => 'Unable to fetch clients'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created client in the database.
     * 
     * @param StoreClientRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreClientRequest $request)
    {
        try {
            // Validate the request data based on the StoreClientRequest rules
            $clientData = $request->validated();

            $client = Client::create($clientData);

            return new ClientResource($client);
        } catch (ValidationException $e) {
            // Handle validation errors and return a 422 response
            return response()->json(['error' => $e->validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            // Log any exception and return a server error response
            $this->logger->log('error', $e->getMessage());

            return response()->json(['error' => 'Unable to create client'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified client.
     * 
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        try {
            // Attempt to find the client by ID, or throw ModelNotFoundException
            $client = Client::findOrFail($id);

            return new ClientResource($client);
        } catch (ModelNotFoundException $e) {
            // Handle case where client is not found and return a 404 response
            return response()->json(['error' => 'Client not found'], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            // Log the exception and return a server error response
            $this->logger->log('error', $e->getMessage());

            return response()->json(['error' => 'Unable to fetch client'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }    
    }

    /**
     * Update the specified client in the database.
     * 
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $id)
    {
        try {
            // Find the client by ID or throw ModelNotFoundException
            $client = Client::findOrFail($id);

            // Validate the request data with specific rules
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:clients,email,' . $client->id,
                'phone' => 'nullable|string|max:15',
            ]);

            // Update the client with the validated data
            $client->update($validatedData);

            // Return the updated client as a resource
            return new ClientResource($client);
        } catch (ValidationException $e) {
            // Handle validation errors and return a 422 response
            return response()->json(['error' => $e->validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (ModelNotFoundException $e) {
            // Handle case where client is not found and return a 404 response
            return response()->json(['error' => 'Client not found'], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            // Log the exception and return a server error response
            $this->logger->log('error', $e->getMessage());

            return response()->json(['error' => 'Unable to update client'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified client from the database.
     * 
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        try {
            // Find the client by ID or throw ModelNotFoundException
            $client = Client::findOrFail($id);

            // Delete the client from the database
            $client->delete();

            // Return a 204 No Content response
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Client not found'], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            $this->logger->log('error', $e->getMessage());
            
            return response()->json(['error' => 'Unable to delete client'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


}