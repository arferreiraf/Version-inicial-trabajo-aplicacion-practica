
import mysql.connector as mysql

conexion = mysql.connect(user="root", db="ferreteria")

cursor = conexion.cursor()	

def validar_usuario(param1,param2):
            
    query = "SELECT userName, contrasena, privilegios, estado  FROM usuario WHERE userName \
    = '%s' AND contrasena = '%s'"

    cursor.execute(query % (param1, param2))

    fila = cursor.fetchall()
    
    if fila:

        for col in fila:

            usua= col[0]
            pas = col[1]
            priv= col[2]
            est = col[3]

        if usua==param1 and pas==param2 and  priv==1 and est==1:
            
            return 1

        if usua==param1 and pas== param2 and priv==2 and est==1:

            return 2

        if usua==param1 and pas==param2 and  priv==1 and est==2:
            
            return 3
        
        if usua==param1 and pas==param2 and  priv==2 and est==2:
            
            return 3
                

    elif not fila:

        return 0
                
        
    



    cursor.close()



  




    

    		

    	


        	

    







    
   
