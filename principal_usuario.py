from tkinter import*
import conexion as con
from tkinter import ttk
import tkinter.messagebox
import entrada_inv_usu as entrada_inv
import salida_inv_usu as salida_inv

def man_usu():


        global vent_inicio

        global fuente 
        fuente = ("Arial", 12, "bold")

        ###################################################

        vent_inicio = Tk()
        vent_inicio.geometry('800x600+300+50')
        vent_inicio.configure(background ='#22A7F0')
        vent_inicio.title('')

        ####################################################

        cuadro1 = Frame(vent_inicio)
        cuadro1.configure(width=640,height=350,  bg='#FFF')
        cuadro1.place(x=160,  y=50)

        cuadro2 = Frame(vent_inicio)
        cuadro2.configure(width=800, height=200, bg='#6C7A89')
        cuadro2.place(x=0, y= 470)

        cuadro3 = Frame(vent_inicio)
        cuadro3.configure(width=150,height=600, bg='#1E8BC3')
        cuadro3.pack(side= LEFT)


        ######################################################

        titulo_version = Label(cuadro3,text='VERSION : 1.0', bg='#22313F',fg='#FFF')
        titulo2 = Label(cuadro1,text='ENTRADA DE INVENTARIO', width=22,bg='#DB402C',font=fuente,fg='white')
        titulo3 = Label(cuadro1,text='SALIDA DE INVENTARIO', width=20,bg='#F6BB03',font=fuente,fg='white')
        titulo2.config(anchor=W)
        titulo3.config(anchor=W)
        
        titulo2.place(x=51,y=35)
        titulo3.place(x=351,y=35)
      
        titulo_version.place(x=40, y= 570)

        #############################################
        imagen2 = PhotoImage(file ='imagen/laptop.png')
        imagen3 = PhotoImage(file ='imagen/exchange.png')
        imagen5 = PhotoImage(file ='imagen/door.png')

        boton2 = Button(cuadro1,image=imagen2, cursor="plus",command=entrada_inv.ent_producto ).place(x=50,y=60)
        boton3 = Button(cuadro1,image=imagen3, cursor="plus",command=salida_inv.sal_producto).place(x=350,y=60)
        
        boton5 = Button(vent_inicio,image=imagen5,command=salir,cursor="plus", width=100, height=100).place(x=650,y=485)

        vent_inicio.mainloop()


def salir():

        vent_inicio.destroy()    
#man_usu()