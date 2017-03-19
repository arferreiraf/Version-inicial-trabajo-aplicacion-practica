import pyttsx
from time import*
import conexion as con
from tkinter import*
import tkinter.messagebox
import principal as pr
import principal_usuario as v_u

fuente2 = ("Arial", 12, "bold")
                                         
def obtener_valores():

 # Obtiene los valores de los Entry's y los pasa a la función
 # que interactúa con la base de datos

        usuario = u.get()

        clave = p.get()

        val = con.validar_usuario(usuario,clave)

# Si el valor retornado es 1 significa que la consulta tuvo éxito

        if val==1:

                engine = pyttsx.init()
                rate = engine.getProperty('rate')
                engine.setProperty('rate', rate-65)
                engine.say('bIENVENIDO al sistema usuario %s'%(usuario))
                engine.runAndWait()

                tkinter.messagebox.showinfo("mensaje","Bienvenido %s"%(usuario))

                ventana.destroy()

                pr.man()

        elif val == 2:
                engine = pyttsx.init()
                rate = engine.getProperty('rate')
                engine.setProperty('rate', rate-85)
                engine.say('bIENVENIDO al sistema usuario %s'%(usuario))
                engine.runAndWait()


                tkinter.messagebox.showinfo("mensaje", "Bienvenido %s"%(usuario))

                ventana.destroy()
                 
                v_u.man_usu()

                        
        elif val == 3:
                engine = pyttsx.init()
                rate = engine.getProperty('rate')
                engine.setProperty('rate', rate-85)
                engine.say('usuario desactivado, contáctese con el administrador')
                engine.runAndWait()

                tkinter.messagebox.showerror('mensaje', 'USUARIO DESACTIVADO \n CONTACTESE CON EL ADMINISTRADOR')
                u.set('')
                p.set('')
                entrada1.focus_set()
        
        elif val==0:

                engine = pyttsx.init()
                rate = engine.getProperty('rate')
                
                engine.setProperty('rate', rate-85)
                engine.say('Ingrese datos válidos no sea imbécil')
                engine.runAndWait()

              
                tkinter.messagebox.showerror("mensaje", "INGRESE DATOS VALIDOS")
                u.set('')
                p.set('')
                entrada1.focus_set()
    

ventana = Tk()
ventana.configure(bg='#D4D5D6')

ventana.title('INGRESO AL SISTEMA')
ventana.geometry('380x310+530+200')

labelframe = LabelFrame(ventana,font=fuente2, text="Login")

def dest():
        ventana.destroy()

###############################################  

u = StringVar()

p = StringVar()

#widgets

etiqueta1 = Label(labelframe, font=('Arial',10),text="Usuario:")
entrada1 = Entry(labelframe, width=35, textvariable = u)
entrada1.focus_set()

etiqueta2 = Label(labelframe,font=('Arial',10), text="Contraseña:")
entrada2 = Entry(labelframe,width=35, textvariable = p, show='*',font=('Arial',10))
acceder  = Button(ventana,width=5,fg='blue',bg='white', font=('Arial',8,'bold'),text="E\n\nN\n\nT\n\nR\n\nA\n\nR", command = obtener_valores)       
salir = Button(ventana,bg='#0077D4',fg='white',width=5,font=('Arial',8,'bold'),text='\nS\n\n A\n\n L\n\n I\n\n R\n',command=dest)
salir.place(x=333,y=130)
acceder.place(x=4,y=130)
        # ubicacion

labelframe.place(x=15,y=15)

etiqueta1.grid(row=0, column=1)

entrada1.grid(row=0, column=2, padx=10)

etiqueta2.grid(row=1, column=1)

entrada2.grid(row=1, column=2, padx=10)



imagen = PhotoImage(file ='imagen/ferret2.png')

userimagen = Label(ventana, image=imagen)
userimagen.place(x=50,y=85)

         
ventana.mainloop()


