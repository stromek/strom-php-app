<?php
declare(strict_types=1);

namespace App\ApiDocs;

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
     * Response
     */
    new OA\Schema(
      schema: "Response:Status",
      type: "string",
      default: "success",
      enum: ["success", "error"]
    ),

    new OA\Schema(
      schema: "Response:Data",
      type: "object"
    ),

    new OA\Schema(
      schema: "Response:Error",
      properties: [
        new OA\Property(property: "code", type: "string", example: "ERROR_CODE"),
        new OA\Property(property: "message", type: "string", example: "Human error description"),
        new OA\Property(property: "details", type: "object", example: "{}", nullable: true),
      ],
      type: "object",
      nullable: true
    ),
    new OA\Schema(
      schema: "Response:Meta",
      properties: [
        new OA\Property(property: "correlation_id", type: "string", example: "5c239d31-5f19-4592-8c47-6d86ce848518"),
        new OA\Property(property: "timestamp", type: "integer", example: 1741210981),
      ],
      type: "object"
    ),
    new OA\Schema(
      schema: "Response:Meta:Paginator",
      properties: [
        new OA\Property(property: "currentPage", type: "integer", example: 1),
        new OA\Property(property: "perPage", type: "integer", example: 10),
        new OA\Property(property: "totalPages", type: "integer", example: 3),
        new OA\Property(property: "totalItems", type: "integer", example: 25),
      ],
      type: "object"
    ),

  ],


  /**
   * Response HTTP
   */
  responses: [
    new OA\Response(
      response: 200,
      description: "OK",
      content: new OA\JsonContent(
        properties: [
          new OA\Property(property: "status", type: "string", example: "error"),
          new OA\Property(property: "error", type: "object", allOf: [
            new OA\Schema(allOf: [
              new OA\Property(ref: "#/components/schemas/Response:Error"),
              new OA\Schema(properties: [
                new OA\Property(property : "code", type: "string", example: "UNAUTHORIZED"),
                new OA\Property(property : "message", type: "string", example: "Missing authorization token"),
              ])
            ]),
          ]),
        ],
      )
    ),

    new OA\Response(
      response: 404,
      description: "Not Found",
      content: new OA\JsonContent(
        properties: [
          new OA\Property(property: "status", type: "string", example: "error"),
          new OA\Property(property: "error", type: "object", allOf: [
            new OA\Schema(allOf: [
              new OA\Property(ref: "#/components/schemas/Response:Error"),
              new OA\Schema(properties: [
                new OA\Property(property : "code", type: "string", example: "NOT_FOUND"),
                new OA\Property(property : "message", type: "string", example: "Resource not found"),
              ])
            ]),
          ]),
        ],
      )
    ),

    new OA\Response(
      response: 401,
      description: "Unauthorized",
      content: new OA\JsonContent(
        properties: [
          new OA\Property(property: "status", type: "string", example: "error"),
          new OA\Property(property: "error", type: "object", allOf: [
            new OA\Schema(allOf: [
              new OA\Property(ref: "#/components/schemas/Response:Error"),
              new OA\Schema(properties: [
                new OA\Property(property : "code", type: "string", example: "UNAUTHORIZED"),
                new OA\Property(property : "message", type: "string", example: "Missing authorization token"),
              ])
            ]),
          ]),
        ],
      )
    ),

    new OA\Response(
      response: 500,
      description: "Internal server error",
      content: new OA\JsonContent(
        properties: [
          new OA\Property(property: "status", type: "string", example: "error"),
          new OA\Property(property: "error", type: "object", allOf: [
            new OA\Schema(allOf: [
              new OA\Property(ref: "#/components/schemas/Response:Error"),
              new OA\Schema(properties: [
                new OA\Property(property : "code", type: "string", example: "INTERNAL_SERVER_ERROR"),
                new OA\Property(property : "message", type: "string", example: "Internal server error"),
              ])
            ]),
          ]),
        ],
      )
    )
  ]
)]
class SwaggerDocs {

}