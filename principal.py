from tkinter import*
import conexion as con
from tkinter import ttk
import tkinter.messagebox
import usuarios as user
import productos as prod
import entrada_inventario as inv_ent
import salida_inventario as inv_sal
from PIL import Image , ImageTk
#funcion ventana principal
def man():
        #parametro base de ventana principal
        global vent_inicio
        vent_inicio = Tk()
        vent_inicio.geometry('1300x690+30+0')
        menu = Menu(vent_inicio)##-- se crea MeNU
        ##menu.post(0, 900)
        vent_inicio.configure(background ='#68C3A3', menu=menu)##--se agrega menu a vent_inicio--
        vent_inicio.title('')       
       
        global fuente   
        fuente = ("Arial", 12, "bold")

        ###################---MENU--######################
        def ayuD():
            vent = Toplevel()
            ho = Label(vent, text='HOla Bienvenidos').place(x=5,y=10)

        filemenu = Menu(menu, tearoff=0)
        filemenu.add_command(label="Nuevo", command=ayuD)
        filemenu.add_command(label="Abrir", command=ayuD)
        filemenu.add_command(label="Guardar", command=ayuD)
        filemenu.add_command(label="Guardar como...", command=ayuD)
        filemenu.add_command(label="Cerrar", command=ayuD)
        filemenu.add_separator()
        filemenu.add_command(label="Exit", command=vent_inicio.destroy)
        menu.add_cascade(label="Archivo", menu=filemenu)
        
        editmenu = Menu(menu, tearoff=0)
        editmenu.add_command(label="Atras", command=ayuD)
        editmenu.add_separator()
        editmenu.add_command(label="Cortar", command=ayuD)
        editmenu.add_command(label="Copiar", command=ayuD)
        editmenu.add_command(label="Pegar", command=ayuD)
        editmenu.add_command(label="Borrar", command=ayuD)
        editmenu.add_command(label="Selecciona Todo", command=ayuD)
        menu.add_cascade(label="Editar", menu=editmenu)

        helpmenu = Menu(menu, tearoff=0)
        helpmenu.add_command(label="Ayuda Index", command=ayuD)
        helpmenu.add_command(label="About...", command=ayuD)
        menu.add_cascade(label="Ayuda", menu=helpmenu)

        cerrarV = Menu(menu, tearoff=0)
        menu.add_checkbutton(label='CERRAR', command=vent_inicio.destroy)
        #cerrarV.add_command( command=vent_inicio.quit)

        

        ####################################################
        #parametro base de ventana principal
        cuadro1 = Frame(vent_inicio)
        cuadro1.configure(width=1035,height=330,  bg='#049372')
        cuadro1.place(x=260,  y=10)

        cuadro2 = Frame(vent_inicio)
        cuadro2.configure(width=1035, height=330, bg='#26C281')
        cuadro2.place(x=260, y= 345)

        cuadro3 = Frame(vent_inicio)
        cuadro3.configure(width=250,height=670, bg='#26A65B')
        cuadro3.place(x=5, y=10)


        ######################################################
        #parametro de titulo de ventana principal
        titulo_version = Label(cuadro3,text='VERSION : 1.0', bg='#22313F',fg='#FFF')
        titulo1 = Label(cuadro1,text='PRODUCTOS', width=12,bg='#E67E22', font= fuente,fg='white')
        titulo2 = Label(cuadro1,text='ENTRADA \nINVENTARIO', width=15,bg='#E67E22',font=fuente,fg='white')
        titulo3 = Label(cuadro1,text='MERMA \nINVENTARIO', width=15,bg='#E67E22',font=fuente,fg='white')
        #titulo5 = Label(cuadro3,text='ADMINISTRACION \n DE USUARIOS',bg='#1E8BC3',font=fuente,fg='#F1F1F1')

        titulo1.config(anchor=W)
        titulo2.config(anchor=NW)
        titulo3.config(anchor=NW)
       # titulo5.config(anchor=W)
        
        titulo1.place(x=16,y=35)
        titulo2.place(x=271,y=20)
        titulo3.place(x=546,y=20)      
        #titulo5.place(x=16,y=50)
        titulo_version.place(x=85, y=630)

        #############################################
        #carga de imagenas para ventana principal
        
        imagen2 = PhotoImage(file ='imagen/laptop.png')
        imagen3 = PhotoImage(file ='imagen/exchange.png')
        
        imagenUser = PhotoImage(file ='imagen/users2.png')
        imagenProv = PhotoImage(file ='imagen/seller.png')
        #--------------------------------------------------------------
        imgS = Image.open('imagen/exchange.png')
        imagenS = imgS.resize((220,220))
        Simg    = ImageTk.PhotoImage(imagenS)


        imgLap = Image.open('imagen/laptop.png')# imagen laptop
        imagenLap = imgLap.resize((220,220))
        lapImg = ImageTk.PhotoImage(imagenLap)

        imgCar = Image.open('imagen/cart.png')#-- Imagen´productos
        imagenCar = imgCar.resize((220,220))
        carImg = ImageTk.PhotoImage(imagenCar)

        #--------------------------------------------------------------
        img2Exit = Image.open('imagen/exit4.png')#-- Imagen´puerta  SALIR
        imExit = img2Exit.resize((115,100))
        imExitt = ImageTk.PhotoImage(imExit)



        img1 = Image.open('imagen/ferr2.png')## Con PILLOW-- Logo ferreteria
        imagenferr1 = img1.resize((247,75)) 
        imagenFerr = ImageTk.PhotoImage(imagenferr1)
        ferrImg = Label(cuadro3,image=imagenFerr)
        ferrImg.place(x=0,y=0)

        img2 = Image.open('imagen/ventas.png')## Con PILLOW-- Img ventas
        imagenV = img2.resize((220 ,220)) 
        imagenVentas = ImageTk.PhotoImage(imagenV)
        imgVentas = Label(cuadro2,image=imagenVentas)
        imgVentas.place(x=30,y=30) 

        img3 = Image.open('imagen/fact.jpg')## Con PILLOW Img facturas
        imagenF = img3.resize((220 ,220)) 
        imagenFactura = ImageTk.PhotoImage(imagenF)
        imgFact = Label(cuadro2,image=imagenFactura)
        imgFact.place(x=275,y=30)

        img4 = Image.open('imagen/dev.png')## Con PILLOW -- Img nota credito
        imagenD = img4.resize((220 ,220)) 
        imagenDevol = ImageTk.PhotoImage(imagenD)
        imgDev = Label(cuadro2,image=imagenDevol)
        imgDev.place(x=520,y=30) 

        img5 = Image.open('imagen/truck.png')## Con PILLOW -- Img Compras
        imagenC = img5.resize((220 ,220)) 
        imagenCompras = ImageTk.PhotoImage(imagenC)
        imgComp = Label(cuadro2,image=imagenCompras)
        imgComp.place(x=770,y=30) 
  
        ##############################

        userimagen = Label(cuadro3, image=imagenUser)
        userimagen.place(x=20,y=120)

        provImagen = Label(cuadro3, image=imagenProv)
        provImagen.place(x=20, y=380)

        #boton para acceder las ventas usuarios, productos, entrada y salida de inventario
        boton  = Button(cuadro1,image=carImg, cursor="plus",command=prod.press_producto).place(x=15, y=60)
        boton2 = Button(cuadro1,image=lapImg, cursor="plus",command=inv_ent.listar_inventario_entrada).place(x=270,y=60)
        boton3 = Button(cuadro1,image=Simg, cursor="plus",command=inv_sal.listar_inventario_salida).place(x=545,y=60)
        boton4 = Button(cuadro3, cursor="plus",bg='#E67E22',command=user.listar_usuario,  width=14,font=fuente,fg='white',text='ADMINISTRACION \nDE USUARIOS').place(x=5,y=300)
        boton6 = Button(cuadro3, cursor="plus",bg='#E67E22',command=user.listar_usuario,  width=16,font=fuente,fg='white',text='ADMINISTRACION \nDE PROVEEDORES').place(x=5,y=560)
        
        #boton salir ventana principal
       ##--boton5 = Button(vent_inicio,image=imExitt,command=salir,cursor="plus", width=100, height=100).place(x=1180,y=560)
       
        vent_inicio.mainloop()

                            
#funcion para salir de ventana principal
def salir():

        vent_inicio.destroy()    
man()




