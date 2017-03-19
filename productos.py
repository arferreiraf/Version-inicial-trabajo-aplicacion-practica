from tkinter import*
import conexion as con
from tkinter import ttk
import tkinter.messagebox

#funcion para crear y modificar productos
def press_producto():    
    global ventan
    #parametro base de ventana productos
    ventan = Toplevel()
    ventan.config(bg='#3A6F9B')
    ventan.geometry('900x695+230-33')
    fuente=("Arial", 12, "bold")

    #boton salir ventana productos
    salir2 = Button(ventan,text='V\nO\nL\nV\nE\nR',command=ventan.destroy,font=('Arial',14,'bold'),bg='#0187FD',fg='#FFF')
    salir2.place(x=855,y=492)
        
    #parametro base de ventana productos
    cont_1 = Frame(ventan)
    cont_1.configure(width=890,height=385 ,bg='#0187FD')
    cont_1.place(x=5,y=6)

    #parametro base de ventana productos
    #################################################
    cont_2 = Frame(cont_1)
    cont_2.configure(width=350,height=356,bg='#0077B9')
    cont_2.place(x=60,y=5)
    tit_c2 = Label(cont_2,text='INGRESAR PRODUCTOS',font=fuente,bg='#0077B9',fg='#FFF').place(x=12,y=1)

    #parametro base de ventana productos
    cont_3 = Frame(cont_1)
    cont_3.configure(width=350,height=356,bg='#0077B9')
    cont_3.place(x=480,y=5)
    tit_c3 = Label(cont_3,text='ACTUALIZAR PRODUCTOS',font=fuente,bg='#0077B9',fg='#FFF').place(x=12,y=1)

    
    ################################################

    #Variables para capturar el valor en los inputs(Entry)
    iid = IntVar()
    eee = StringVar()
    iii = IntVar()
    ooo = IntVar()
    uuu = StringVar()
    aaa = IntVar()

    
    ##FORMULARIO DE ACTUALIZACION
    p1 = Entry(cont_3,width=11,state='disable',textvariable=iid,justify=CENTER).place(x=55,y=260)
    it1 = Label(cont_3,width=7,text='ID').place(x=70,y=240)

    p3 = Entry(cont_3,width=40,textvariable=eee,justify=CENTER).place(x=55,y=60)
    it3 = Label(cont_3,width=10,text='Producto').place(x=223,y=40)

    p2 = Entry(cont_3,width=40,state='disable',textvariable=iii,justify=CENTER).place(x=55,y=110)
    it2 = Label(cont_3,width=10,text='Stock').place(x=223,y=90)            

    p3 = Entry(cont_3,width=40,textvariable=ooo,justify=CENTER).place(x=55,y=160)
    it3 = Label(cont_3,width=10,text='Precio').place(x=223,y=140)

    p4 = Entry(cont_3,width=40,textvariable=uuu,justify=CENTER).place(x=55,y=210)
    it4 = Label(cont_3,width=10,text='Descripcion').place(x=223,y=190)

    titulo_est = Label(cont_3,width=10,text='Estado').place(x=223,y=240)
    mod_estado_r1=Radiobutton(cont_3,text='Activo',font=("Arial",11),variable=aaa,value=1,bg='#fff').place(x=151,y=260)
    mod_estado_r2=Radiobutton(cont_3,text='Inactivo',font=("Arial",11),variable=aaa,value=2,bg='#fff').place(x=220,y=260)

    
    #Variables para capturar el valor en los inputs(Entry)
    producto_e = StringVar()
    stock_e = IntVar()
    precio_e = IntVar()
    descripcion_e = StringVar()
    estado_e = IntVar()

    ##FORMULARIO INGRESAR      
    titulo_nom = Label(cont_2,width=10,text='Producto').place(x=223,y=40)
    in_nombre = Entry(cont_2,width=40,textvariable=producto_e).place(x=55,y=60)
        
    titulo_stock = Label(cont_2,width=10,text='Stock').place(x=223,y=90)
    in_stock = Entry(cont_2,width=40,textvariable=stock_e).place(x=55,y=110)

    titulo_precio = Label(cont_2,width=10,text='Precio').place(x=223,y=140)
    in_precio = Entry(cont_2,width=40,textvariable=precio_e).place(x=55,y=160)
        
    titulo_descrip = Label(cont_2,width=10,text='Descripcion').place(x=223,y=190)
    in_descripcion = Entry(cont_2,width=40,textvariable=descripcion_e).place(x=55,y=210)
     
    titulo_estado = Label(cont_2,width=10,text='Estado').place(x=223,y=240)      
    in_estado_r1=Radiobutton(cont_2,text='Activo',font=("Arial",11),variable=estado_e,value=1,bg='#fff').place(x=151,y=260)
    in_estado_r2=Radiobutton(cont_2,text='Inactivo',font=("Arial",11),variable=estado_e,value=2,bg='#fff').place(x=220,y=260)
    ##DEFINE FUNCION INSERTA()
   
    def inserta():

        try:

            prod = producto_e.get().lower()
            st = stock_e.get()
            prec = precio_e.get()
            descrip = descripcion_e.get().lower()
            est = estado_e.get()                       

            if st <= -1 or prec <= -1:

                tkinter.messagebox.showinfo("Mensaje","El campo Stock o Precio no acepta valores negativos")
                ventan.destroy()               
                press_producto()

            else:   

                if prod and st and prec and descrip and est:

                    con.cursor.execute("INSERT INTO productos(nombre_producto, stock_producto,Precio,\
                    descripcion_producto, Estado_idEstado)VALUES(%s,%s,%s,%s,%s)",(prod,st,prec,descrip,est))
                    con.conexion.commit()
                    ventan.destroy()
                    
                    tkinter.messagebox.showinfo("mensaje","Datos Guardados")
                    press_producto()
                
                else:

                     tkinter.messagebox.showinfo("mensaje","No ingrese campos en blanco\n\
    Llene todos los campos del Formulario")
                     ventan.destroy()
                     press_producto()

        except ValueError:

            tkinter.messagebox.showinfo("mensaje","LOS CAMPOS STOCK Y PRECIO ACEPTAN SÓLO VALORES \
                NUMÉRICOS NO INGRESE LETRAS  ") 
            ventan.destroy()
            press_producto()

        except con.mysql.IntegrityError as err:

            tkinter.messagebox.showinfo("Mensaje","El producto ingresado ya existe \n ingrese otro nombre".upper())
            ventan.destroy()
            press_producto()    

        except:

            tkinter.messagebox.showinfo('mensaje','Error')
            ventan.destroy()
            press_producto()
    

    ##DEFINICION FUNCION ACTUALIZA()
    def actualiza():

        try:

            id_prod=int(iid.get())
            prod = eee.get().lower()
            st = int(iii.get())
            prec = int(ooo.get())
            descrip = uuu.get().lower()
            est = aaa.get()  

            if st <= -1 or prec <= -1:

                tkinter.messagebox.showinfo("Mensaje","El campo Stock o Precio no acepta valores negativos")
                ventan.destroy()
                press_producto()

            else:    

                if prod and st and prec and descrip and est:
                  
                    con.cursor.execute("UPDATE productos SET nombre_producto=%s,stock_producto=%s,Precio=%s,\
                    descripcion_producto=%s, Estado_idEstado=%s WHERE idProductos=%s",(prod,st,prec,descrip,est,id_prod))
                    con.conexion.commit()
                    ventan.destroy()
                    
                    tkinter.messagebox.showinfo("mensaje","Datos Modificados")
                    press_producto()
                
                else:

                     tkinter.messagebox.showinfo("mensaje","No ingrese campos en blanco\n\
    Llene los todos campos del Formulario")
                     ventan.destroy()
                     press_producto()

        except ValueError:

            tkinter.messagebox.showinfo("mensaje","LOS CAMPOS STOCK Y PRECIO ACEPTAN SÓLO VALORES \
                NUMÉRICOS NO INGRESE LETRAS  ") 
            ventan.destroy()
            press_producto()

        except con.mysql.IntegrityError as err:
            tkinter.messagebox.showinfo("Mensaje","El producto ingresado ya existe \n ingrese otro nombre".upper())
            ventan.destroy()
            press_producto()    

        except:

            tkinter.messagebox.showinfo('mensaje','Error')
            ventan.destroy()
            press_producto()

    ##CREACION BOTONES DE EJECUCION DEL CRUD
    bot_inser = Button(cont_2,text='INSERTA \nPRODUCTO',command=inserta).place(x=140,y=310)
    btn_act = Button(cont_3,text='ACTUALIZA \nPRODUCTO',command=actualiza).place(x=140,y=310)
    
    
    ##VENTANA CONTENEDORA DE LA GRILLA DE PRODUCTOS
    top_cont = Frame(ventan) 
    top_cont.configure(bg='#FFF')
    top_cont.place(x=64,y=391)

    title_produc=Label(cont_1,width=20,text='LISTADO DE PRODUCTOS').place(x=60,y=364)  
    tit_doblecl=Label(cont_1,text='Seleccione con Doble Click').place(x=682,y=364)
    
    ##GRILLA O LISTAR DE PRODUCTOS      
    tree = ttk.Treeview(top_cont,height=15,selectmode="browse",columns=('ID',"A","B","C","D","E","F"))
    tree.pack(expand=YES, fill=BOTH)

    tree.heading('#0', text='')

    tree.column('#0',width=0,minwidth=0)
        
    tree.heading("ID", text="ID")
    tree.column("ID",width=60,anchor=CENTER)
        
    tree.heading("A", text="NOMBRE")   
    tree.column("A",width=200,anchor=CENTER) 
        
    tree.heading("B", text="STOCK")   
    tree.column("B",width=50,anchor=CENTER)
        
    tree.heading("C",text='PRECIO')
    tree.column('C',width=90,anchor=CENTER)
        
    tree.heading("D",text='DESCRIPCION')
    tree.column('D',width=280,anchor=CENTER)
        
    tree.heading("E",text='IDESTADO')
    tree.column('E',width=1,minwidth=0)

    tree.heading("F",text='ESTADO')
    tree.column('F',width=88,anchor=CENTER)
   

    query1 = "SELECT idProductos, nombre_producto, stock_producto,Precio, descripcion_producto, Estado_idEstado, estados FROM productos  INNER JOIN estados ON productos.Estado_idEstado=estados.idEstado order by idProductos DESC"
       
    con.cursor.execute(query1)
    filas = con.cursor.fetchall()
    
    for col in filas:
              
        tree.insert('','end',values=(str(col[0])+'\t',str(col[1]),str(col[2]),str(col[3]),str(col[4]),str(col[5]),str(col[6])))
   
    tree.pack()
    #funcion para selecionar lista de usuario          
    def selecItem(a):
        curItem = tree.focus()
        
        is1 = tree.set(curItem,'ID')
        iss = tree.set(curItem,'A')
        ess = tree.set(curItem,'B')
        oss = tree.set(curItem,'C')
        uss = tree.set(curItem,'D')
        ass = tree.set(curItem,'E')

        iid.set(is1)
        eee.set(iss)
        iii.set(ess)
        ooo.set(oss)
        uuu.set(uss)
        aaa.set(ass)

    #evento doble click para seleccionar datos en la lista 
    tree.bind('<Double-1>',selecItem)