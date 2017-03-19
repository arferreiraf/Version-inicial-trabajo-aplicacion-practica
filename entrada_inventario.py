from tkinter import*
import conexion as con
from tkinter import ttk
import tkinter.messagebox

def listar_inventario_entrada():
	global listar_inventario_entrada
	#parametro base de ventana de entrada de inventario
	ventana_inv_ent=Toplevel()
	ventana_inv_ent.config(bg='#3A6F9B')
	ventana_inv_ent.geometry('1010x630+160+20')
	fuente=("Arial",12,"bold")

	#boton salir entrada de inventario
	salir_inv = Button(ventana_inv_ent,text='V\nO\nL\nV\nE\nR',command=ventana_inv_ent.destroy,font=('Arial',14,'bold'),bg='#0187FD',fg='#FFF')
	salir_inv.place(x=973,y=440)

	#parametro base de ventana de entrada de inventario
	base_inv = Frame(ventana_inv_ent)
	base_inv.configure(width=998,height=282,bg='#0187FD')
	base_inv.place(x=5,y=6)

	#parametro base de ventana de entrada de inventario
	base_inv_insert=Frame(ventana_inv_ent)
	base_inv_insert.configure(width=402,height=250,bg='#03A9F4')
	base_inv_insert.place(x=15,y=10)
	titulo_base_inv_insert=Label(base_inv_insert,text='REGISTRAR MOVIMIENTO DE ENTRADA',font=fuente,bg='#03A9F4',fg='#FFF').place(x=40,y=1)

	#parametro base de ventana de entrada de inventario
	base_inv_update=Frame(ventana_inv_ent)
	base_inv_update.configure(width=542,height=250,bg='#03A9F4')
	base_inv_update.place(x=425,y=10)
	titulo_base_inv_update=Label(base_inv_update,text='MODIFICAR MOVIMIENTO DE ENTRADA',font=fuente,bg='#03A9F4',fg='#FFF').place(x=130,y=1)

	#Variables para capturar el valor en los inputs(Entry)
	ins_producto=StringVar()
	ins_stock=IntVar()
	ins_descrip=StringVar()
	ins_id=IntVar()

	##FORMULARIO INGRESAR
	titulo_nom=Label(base_inv_insert,width=10,text='Producto').place(x=223,y=40)
	inv_insert_nombre=Entry(base_inv_insert,width=40,state='disable',textvariable=ins_producto).place(x=55,y=60)

	titulo_stock=Label(base_inv_insert,text='Cantidad a Ingresar').place(x=191,y=90)
	inv_insert_stock=Entry(base_inv_insert,width=40,textvariable=ins_stock).place(x=55,y=110)

	titulo_desc=Label(base_inv_insert,text='Observaciones Ingreso').place(x=174,y=140)
	inv_insert_desc=Entry(base_inv_insert,width=40,textvariable=ins_descrip).place(x=55,y=160)

	titulo_id=Label(base_inv_insert,width=5,text='ID').place(x=78,y=190)
	inv_insert_id=Entry(base_inv_insert,width=10,state='disable',textvariable=ins_id).place(x=55,y=210)

	#funcion para insertar datos 
	def ins_inv():

		try:
			descri_pro=ins_descrip.get().lower()
			id_pro=int(ins_id.get())
			stock_pro=int(ins_stock.get())

			if stock_pro <= 0:
				tkinter.messagebox.showinfo("Mensaje","El campo Stock no acepta valores 0 y negativos")
				ventana_inv_ent.destroy()
				listar_inventario_entrada()
			else:	
				if descri_pro and id_pro and stock_pro:
					con.cursor.execute("INSERT INTO ingresos(id_ingreso, fecha_ingreso, descripcion_ingreso, id_producto, cantidad) VALUES (null,now(),%s,%s,%s)",(descri_pro,id_pro,stock_pro))
					con.conexion.commit()
					ventana_inv_ent.destroy()

					tkinter.messagebox.showinfo("mensaje","Datos Guardados")
					listar_inventario_entrada()
				else:
					tkinter.messagebox.showinfo("mensaje","No ingrese campos en blanco\n\
	Llene todos los campos del Formulario")
					ventana_inv_ent.destroy()
					listar_inventario_entrada()
		except ValueError:
			tkinter.messagebox.showinfo("mensaje","EL CAMPO STOCK ACEPTAN SÓLO VALORES \
                NUMÉRICOS NO INGRESE LETRAS")
			ventana_inv_ent.destroy()
			listar_inventario_entrada()
		except:
			tkinter.messagebox.showinfo('mensaje','Error')
			ventana_inv_ent.destroy()
			listar_inventario_entrada()
	#boton para ingresar entrada de inventario
	bot_ins_inv=Button(base_inv_insert,text='Ingresar',command=ins_inv).place(x=223,y=200)


	#parametro grafico de lista de entrada de inventario
	base_lista_inv=Frame(ventana_inv_ent)
	base_lista_inv.place(x=14,y=287)
	
	title_prod=Label(base_inv,width=20,text='LISTADO DE PRODUCTOS').place(x=10,y=260)
	tit_doblecl=Label(base_inv,text='Seleccione con Doble Click').place(x=264,y=260)

	#parametro graficos de lista de entrada de inventario
	inv=ttk.Treeview(base_lista_inv,height=15,selectmode="browse",columns=('ID','PRODUCTO','STOCK','DESCRIPCION','IDESTADO'))
	inv.pack(expand=YES, fill=BOTH)

	inv.heading('#0',text='')
	inv.column('#0',width=0,minwidth=0)

	inv.heading("ID",text="ID")
	inv.column("ID",width=50,anchor=CENTER)

	inv.heading("PRODUCTO",text="PRODUCTO")
	inv.column("PRODUCTO",width=130,anchor=CENTER)

	inv.heading("STOCK",text="STOCK")
	inv.column("STOCK",width=50,anchor=CENTER)

	inv.heading("DESCRIPCION",text="DESCRIPCION")
	inv.column("DESCRIPCION",width=170,anchor=CENTER)

	inv.heading("IDESTADO",text="IDESTADO")
	inv.column("IDESTADO",width=0,minwidth=0)

	query1="SELECT 	idProductos,nombre_producto,stock_producto,descripcion_producto, Estado_idEstado FROM productos WHERE Estado_idEstado=1  ORDER BY nombre_producto ASC "

	con.cursor.execute(query1)
	filas = con.cursor.fetchall()

	for col in filas:
		inv.insert('','end',values=(str(col[0])+'\t',str(col[1]),str(col[2]),str(col[3]),str(col[4])))

	inv.pack()

	#funcion para seleccionar lista de producto
	def selecEnt_inv(a):
		curEnt_inv=inv.focus()

		saa=inv.set(curEnt_inv,'ID')
		see=inv.set(curEnt_inv,'PRODUCTO')
		sii=inv.set(curEnt_inv,'DESCRIPCION')

		ins_id.set(saa)
		ins_producto.set(see)

	#evento doble click para seleccionar datos en la lista
	inv.bind('<Double-1>',selecEnt_inv)

#*****************************************************************************************
	#Variables para capturar el valor en los inputs(Entry)
	up_producto=StringVar()
	up_stock=IntVar()
	up_descrip=StringVar()
	up_id=IntVar()

	#formulario de modificar
	titulo_nom=Label(base_inv_update,width=10,text='Producto').place(x=323,y=40)
	inv_update_nombre=Entry(base_inv_update,width=40,state='disable',textvariable=up_producto).place(x=155,y=60)

	titulo_stock=Label(base_inv_update,text='Modificar Cantidad a Ingresar').place(x=237,y=90)
	inv_update_nombre=Entry(base_inv_update,width=40,textvariable=up_stock).place(x=155,y=110)

	titulo_desc=Label(base_inv_update,text='Modificar Observaciones Ingreso').place(x=220,y=140)
	inv_update_nombre=Entry(base_inv_update,width=40,textvariable=up_descrip).place(x=155,y=160)

	titulo_id=Label(base_inv_update,width=5,text='ID').place(x=178,y=190)
	inv_update_nombre=Entry(base_inv_update,width=10,state='disable',textvariable=up_id).place(x=155,y=210)

	#funcion para modificar listado de inventario
	def mod_inv():
		try:
			descri_up=up_descrip.get().lower()
			id_up=int(up_id.get())
			stock_up=int(up_stock.get())

			if stock_up <= 0:
				tkinter.messagebox.showinfo("Mensaje","El campo Stock no acepta valores 0 y negativos")
				ventana_inv_ent.destroy()
				listar_inventario_entrada()
			else:	
				if descri_up and id_up and stock_up:
					con.cursor.execute("UPDATE ingresos SET descripcion_ingreso=%s, cantidad=%s WHERE id_ingreso=%s",(descri_up,stock_up,id_up))
					con.conexion.commit()
					ventana_inv_ent.destroy()

					tkinter.messagebox.showinfo("mensaje","Datos Guardados")
					listar_inventario_entrada()
				else:
					tkinter.messagebox.showinfo("mensaje","No ingrese campos en blanco\n\
	Llene todos los campos del Formulario")
					ventana_inv_ent.destroy()
					listar_inventario_entrada()
		except ValueError:
			tkinter.messagebox.showinfo("mensaje","EL CAMPO STOCK ACEPTAN SÓLO VALORES \
                NUMÉRICOS NO INGRESE LETRAS")
			ventana_inv_ent.destroy()
			listar_inventario_entrada()
		except:
			tkinter.messagebox.showinfo('mensaje','Error')
			ventana_inv_ent.destroy()
			listar_inventario_entrada()

	#boton para modificar entrada de inventario
	bot_upd_inv=Button(base_inv_update,text='Ingresar',command=mod_inv).place(x=293,y=200)

	#parametro grafico de lista de entrada de inventario
	base_lista_inv=Frame(ventana_inv_ent)
	base_lista_inv.place(x=425,y=287)
	
	title_prod=Label(base_inv,text='REGISTRO DE INGRESOS REALIZADOS').place(x=421,y=260)
	tit_doblecl=Label(base_inv,text='Seleccione con Doble Click').place(x=814,y=260)

	#parametro graficos de lista de entrada de inventario
	inv_up=ttk.Treeview(base_lista_inv,height=15,selectmode="browse",columns=('ID','FECHA','DESCRIPCION','IDPRODUCTO','PRODUCTO','STOCK'))
	inv_up.pack(expand=YES, fill=BOTH)

	inv_up.heading('#0',text='')
	inv_up.column('#0',width=0,minwidth=0)

	inv_up.heading("ID",text="ID")
	inv_up.column("ID",width=35,anchor=CENTER)

	inv_up.heading("FECHA",text="FECHA INGRESO")
	inv_up.column("FECHA",width=115,anchor=CENTER)

	inv_up.heading("DESCRIPCION",text="OBSERVACION INGRESO")
	inv_up.column("DESCRIPCION",width=140,anchor=CENTER)

	inv_up.heading("IDPRODUCTO",text="IDPRODUCTO")
	inv_up.column("IDPRODUCTO",width=0,minwidth=0)

	inv_up.heading("PRODUCTO",text="PRODUCTO")
	inv_up.column("PRODUCTO",width=115,anchor=CENTER)

	inv_up.heading("STOCK",text="CANTIDAD INGRESADA")
	inv_up.column("STOCK",width=135,anchor=CENTER)


	query1="SELECT id_ingreso, fecha_ingreso, descripcion_ingreso, id_producto, nombre_producto, cantidad  FROM ingresos INNER JOIN productos ON ingresos.id_producto=productos.idProductos ORDER BY fecha_ingreso DESC"

	con.cursor.execute(query1)
	filas = con.cursor.fetchall()

	for col in filas:
		inv_up.insert('','end',values=(str(col[0])+'\t',str(col[1]),str(col[2]),str(col[3]),str(col[4]),str(col[5])))

	inv_up.pack()

	#funcion para seleccionar lista de producto
	def selecUp_inv(b):
		curUp_inv=inv_up.focus()

		saa=inv_up.set(curUp_inv,'ID')
		see=inv_up.set(curUp_inv,'PRODUCTO')
		sii=inv_up.set(curUp_inv,'DESCRIPCION')
		soo=inv_up.set(curUp_inv,'STOCK')
		
		up_id.set(saa)
		up_producto.set(see)
		up_descrip.set(sii)
		up_stock.set(soo)
	#evento doble click para selcecionar datos en la lista
	inv_up.bind('<Double-1>',selecUp_inv)