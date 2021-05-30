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
    **Conteúdo:** <br />
`{message: "Transação realizada com sucesso!", data: { id: 10, amount: 100, payer_id: 1, payee_id:2 }}`
   
