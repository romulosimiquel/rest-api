**Transação monetária entre usuários**
----
_Simula uma transferência de valores entre dois usuários retornando as informções em json_

* **URL**

  /transaction/
  
* **Método:**

  `POST`
 
* **Parâmetros da URL**

  Nenhum.

* **Body da requisiçãoo**

  **Required:**

  amount=[float]

  payer_id=[integer]

  payee_id=[integer]
  
  * **Resposta de sucesso:**
 
  * **Codigo:** 200 <br />
    **Conteúdo:** `{'Transação realizada com sucesso!'}`
    
* **Mensagens de erro:**

  * **Codigo:** 401 UNAUTHORIZED <br />
    **Conteúdo:** `{ error : "Log in" }`

  * **Codigo:** 422 UNPROCESSABLE ENTRY <br />
    **Conteúdo:** `{ error : "Email Invalid" }`
    
