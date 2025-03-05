<?php
declare(strict_types=1);

namespace App\Controller\Docs;

use App\Api\Response\ResponseInterface;
use OpenApi\Attributes as OA;


#[OA\Info(
  version: "0.1",
  description: "Testovací aplikace pro testování.",
  title: "Strom API",
)]
#[OA\SecurityScheme(
  securityScheme: "BearerAuth",
  type: "http",
  description: "Unikátní auth token klienta",
  scheme: "bearer"
)]
#[OA\OpenApi(
  security: [
    ["BearerAuth" => []]
  ],
)]

#[OA\Components(
  schemas: [

    /**
     * General objects
     *
     */
    new OA\Schema(
      schema: "DateTimeInterface",
      properties: [
        new OA\Property(property: "date", type: "string", format: "date-time", example: "2025-05-01 14:30:00"),
        new OA\Property(property: "timezone_type", type: "integer", example: 3),
        new OA\Property(property: "timezone", type: "string", example: "UTC")
      ],
      type: "object"
    ),

    /**
     * Generic entity
     */
    new OA\Schema(
      schema: "ResponseEntityList", properties: [
      new OA\Property(
        property: "error", properties: [
          new OA\Property(property: "code", type: "integer", example: 0, nullable: true),
          new OA\Property(property: "text", type: "string", example: null, nullable: true)
        ], type: "object", nullable: true
      ),
      new OA\Property(property: "payload", type: "array", items: new OA\Items(ref: "#/components/schemas/ResponseEntityPayload"))
    ], type: "object"
    ),


    new OA\Schema(
      schema: "ResponseEntityPayload", properties: [
        new OA\Property(property: "version", type: "string", example: "1.0", nullable: true),
        new OA\Property(property: "entity", type: "string", example: "EntityName"),
        new OA\Property(
          property: "attributes",
          type: "object",
        )
      ], type: "object"
    ),


    new OA\Schema(
      schema: "ResponseEntity", properties: [
        new OA\Property(
          property: "error", properties: [
            new OA\Property(property: "code", type: "integer", example: 0, nullable: true),
            new OA\Property(property: "text", type: "string", example: null, nullable: true)
          ], type: "object", nullable: true
        ),
        new OA\Property(property: "payload", ref: "#/components/schemas/ResponseEntityPayload")
      ], type: "object"
    ),


    /**
     * Entity properties
     */
    new OA\Schema(
      schema: "UserEntityHash",
      type: "string",
      pattern: "^[a-zA-Z0-9]{10}\$",
      example: "dZ8x9XcVTx"
    ),

    new OA\Schema(
      schema: "ThreadEntityHash",
      type: "string",
      pattern: "^[a-zA-Z0-9]{100}\$",
      example: "dZ8x9XcVTx..."
    ),
  ],


  /**
   * Response HTTP
   */
  responses: [
    new OA\Response(
      response: 401,
      description: "HTTPUnauthorized",
      content: new OA\JsonContent(
        properties: [
          new OA\Property(
            property: "error", properties: [
              new OA\Property(property: "code", type: "integer", example: 401),
              new OA\Property(property: "text", type: "string", example: "Unauthorized")
            ], type: "object"
          ),
          new OA\Property(
            property: "payload", example: null, oneOf: [
              new OA\Schema(type: "object"),
              new OA\Schema(type: "array", items: new OA\Items()),
              new OA\Schema(type: "string"),
              new OA\Schema(type: "number"),
              new OA\Schema(type: "boolean"),
              new OA\Schema(type: "null"),
            ]
          )
        ]
      )
    ),
    new OA\Response(
      response: 500,
      description: "Internal error",
      content: new OA\JsonContent(
        properties: [
          new OA\Property(
            property: "error", properties: [
            new OA\Property(property: "code", type: "integer", example: 500),
            new OA\Property(property: "text", type: "string", example: "Internal error.")
          ], type: "object"
          ),
          new OA\Property(
            property: "payload", example: null, oneOf: [
            new OA\Schema(type: "object"),
            new OA\Schema(type: "array", items: new OA\Items()),
            new OA\Schema(type: "string"),
            new OA\Schema(type: "number"),
            new OA\Schema(type: "boolean"),
            new OA\Schema(type: "null"),
          ]
          )
        ]
      )
    )
  ]
)]
class DocsController extends \App\Controller\HTMLController {

  private \OpenApi\Annotations\OpenApi $openApi;

  public function __construct(\App\Xml\XMLBuilder $xmlBuilder) {
    parent::__construct($xmlBuilder);
    $openApi = \OpenApi\Generator::scan([SRC_DIR]);

    if(!$openApi) {
      throw new \App\Controller\ControllerException("OpenAPI generator failed");
    }

    $this->openApi = $openApi;
  }


  public function index(): ResponseInterface {
    return $this->renderHTML("Docs.index.xsl");
  }


  public function swagger(): ResponseInterface {
    $Response =  $this->responseFactory->createResponse(
      \App\Http\Enum\StatusCodeEnum::STATUS_OK,
      $this->openApi->toJson(),
      "application/json"
    );

    $Response->addHeader("Access-Control-Allow-Origin", "*");

    return $Response;
  }


}