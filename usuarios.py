from tkinter import*
import conexion as con
from tkinter import ttk
import tkinter.messagebox
#funcion para crear y eliminar usuarios

def listar_usuario():
    global listar_usuario
    #parametro base de ventana usuario
    ventana_lista=Toplevel()   
    ventana_lista.config(bg='#3A6F9B')
    ventana_lista.geometry('900x600+290+70')
    fuente=("Arial", 12, "bold")

    #boton salir ventana usuario
    salir_user = Button(ventana_lista,text='V\nO\nL\nV\nE\nR',command=ventana_lista.destroy,font=('Arial',14,'bold'),bg='#0187FD',fg='#FFF')
    salir_user.place(x=800,y=440)

    #parametro base de ventana usuario
    base_1 = Frame(ventana_lista)
    base_1.configure(width=890,height=285 ,bg='#0187FD')
    base_1.place(x=5,y=6)

    #parametro base de ventana usuario
    base_insert=Frame(ventana_lista)
    base_insert.configure(width=298,height=250,bg='#03A9F4')
    base_insert.place(x=150,y=10)
    title_base_insert=Label(base_insert,text='INGRESAR USUARIOS',font=fuente,bg='#03A9F4',fg='#FFF').place(x=12,y=1)

    #parametro base de ventana usuario
    base_delete=Frame(ventana_lista)
    base_delete.configure(width=298,height=250,bg='#03A9F4')
    base_delete.place(x=455,y=10)
    title_base_delete=Label(base_delete,text='ELIMINAR USUARIOS',font=fuente,bg='#03A9F4',fg='#FFF').place(x=12,y=1)

    #Variables para capturar el valor en los inputs(Entry)
    nombre_user = StringVar()
    contra_user = StringVar()
    privi_user = IntVar()

    delete_nombre_user=StringVar()
    delete_contra_user=StringVar()
    delete_privi_user=IntVar()

 	##FORMULARIO INGRESAR USUARIOS               
    titulo_nom = Label(base_insert,width=10,text='Nombre').place(x=188,y=40)
    user_nombre = Entry(base_insert,width=40,textvariable=nombre_user).place(x=20,y=60)
        
    titulo_contrasena = Label(base_insert,width=10,text='Contraseña').place(x=188,y=90)
    user_contrasena = Entry(base_insert,width=40,textvariable=contra_user).place(x=20,y=110)

    titulo_privilegios = Label(base_insert,width=10,text='Privilegio').place(x=188,y=140)

    user_privilegios_r1=Radiobutton(base_insert,text='Administrador',font=("Arial",11),variable=privi_user,value=1,bg='#fff').place(x=64,y=160)
    user_privilegios_r2=Radiobutton(base_insert,text='Usuario',font=("Arial",11),variable=privi_user,value=2,bg='#fff').place(x=184,y=160)

    ##FORMULARIO ELIMINAR USUARIOS               
    titulo_elimi_nom = Label(base_delete,width=10,text='Nombre').place(x=188,y=40)
    eliminar_nombre = Entry(base_delete,width=40,state='disable',textvariable=delete_nombre_user).place(x=20,y=60)
        
    titulo_elimi_contra = Label(base_delete,width=10,text='Contraseña').place(x=188,y=90)
    eliminar_contrasena = Entry(base_delete,width=40,state='disable',textvariable=delete_contra_user).place(x=20,y=110)

    titulo_elimi_privi = Label(base_delete,width=10,text='Privilegio').place(x=188,y=140)
    eliminar_privilegios_r1=Radiobutton(base_delete,state='disable',text='Administrador',font=("Arial",11),variable=delete_privi_user,value=1,bg='#ffffff').place(x=64,y=160)
    eliminar_privilegios_r2=Radiobutton(base_delete,state='disable',text='Usuario',font=("Arial",11),variable=delete_privi_user,value=2,bg='#ffffff').place(x=184,y=160)

    #funcion para insertar usuarios
    def insert_user():
        try:
            nomb=nombre_user.get().lower()
            cont=contra_user.get()
            priv=privi_user.get()

            if nomb and cont and priv:
                con.cursor.execute("INSERT INTO usuario (userName, contrasena, privilegios) values (%s,%s,%s)",(nomb,cont,priv))
                con.conexion.commit()
                ventana_lista.destroy()
                tkinter.messagebox.showinfo("Mensaje","Datos Guardados")
                listar_usuario()
            else:
                tkinter.messagebox.showinfo("Mensaje","Error, Falta Ingresar Datos")
                ventana_lista.destroy()
                listar_usuario()
        except ValueError:
            tkinter.messagebox.showinfo("Mensaje","Error")
            ventana_lista.destroy()
            listar_usuario()

    #boton para ingresar usuario
    boton_insert=Button(base_insert,text='Insertar Usuario',command=insert_user)
    boton_insert.place(x=100,y=200)
    
    #funcion para eliminar usuarios
    def delet_user():
        try:
            delet_nomb=delete_nombre_user.get().lower()
            delet_cont=delete_contra_user.get()
            delet_priv=delete_privi_user.get()
            if delet_nomb and delet_cont and delet_priv:
                con.cursor.execute("DELETE FROM usuario WHERE userName=%s and contrasena=%s and privilegios=%s",(delet_nomb,delet_cont,delet_priv))
                con.conexion.commit()
                ventana_lista.destroy()

                tkinter.messagebox.showinfo("Mensaje","Datos Eliminados")
                listar_usuario()
            else:
                tkinter.messagebox.showinfo("Mensaje","Error, Falta Ingresar Datos")
                ventana_lista.destroy()
                listar_usuario()  
        except ValueError:
            tkinter.messagebox.showinfo("Mensaje","Error")
            ventana_lista.destroy()
            listar_usuario()

    #boton para eliminar usuario
    boton_delete=Button(base_delete,text='Eliminar Usuario',command=delet_user)
    boton_delete.place(x=100,y=200)

    #parametro graficos de lista de usuario
    base_lista=Frame(ventana_lista)
    base_lista.place(x=150,y=290)

    title_user=Label(base_1,width=20,text='LISTADO DE USUARIOS').place(x=146,y=263)  
    tit_doblecl=Label(base_1,text='Seleccione con Doble Click').place(x=601,y=263)      

    users = ttk.Treeview(base_lista,height=15 ,selectmode="browse",columns=('ID','USUARIOS',"CONTRASEÑA","IDPRIVILEGIOS","PRIVILEGIOS"))
    users.pack(expand=YES, fill=BOTH)

    users.heading('#0', text='')

    users.column('#0',width=0,minwidth=0)

    users.heading("ID", text="ID")
    users.column("ID",width=1,minwidth=0)

    users.heading("USUARIOS", text="USUARIOS")
    users.column("USUARIOS",anchor=CENTER)

    users.heading("CONTRASEÑA", text="CONTRASEÑA")
    users.column("CONTRASEÑA",anchor=CENTER)

    users.heading("IDPRIVILEGIOS", text="IDPRIVILEGIOS")
    users.column("IDPRIVILEGIOS",width=1,minwidth=0)

    users.heading("PRIVILEGIOS", text="PRIVILEGIO")
    users.column("PRIVILEGIOS",anchor=CENTER)

    query1 = "SELECT idUsuario, userName, contrasena, privilegios, nombre_privilegios FROM usuario INNER JOIN privilegios on usuario.privilegios=privilegios.idprivilegios ORDER BY idUsuario DESC"
    
    con.cursor.execute(query1)
    filas = con.cursor.fetchall()
 
    
    for col in filas:           
        users.insert('','end',text=str(col[0]), values=(str(col[0]),str(col[1]),str(col[2]),str(col[3]),str(col[4])))            
    users.pack()
   
    #funcion para selecionar lista de usuario
    def selecUser(a):
        curUser = users.focus()
                            
        aas = users.set(curUser,'USUARIOS')
        ees = users.set(curUser,'CONTRASEÑA')
        iis = users.set(curUser,'IDPRIVILEGIOS')

        delete_nombre_user.set(aas)
        delete_contra_user.set(ees)
        delete_privi_user.set(iis)
        
    #evento doble click para seleccionar datos en la lista 
    users.bind('<Double-1>',selecUser)



#listar_usuario()    

 
