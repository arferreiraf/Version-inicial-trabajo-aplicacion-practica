from tkinter import*
import conexion as con
from tkinter import ttk
import tkinter.messagebox

def listar_inventario_salida():
	global listar_inventario_salida
	#parametro base de ventana inventario
	ventana_inv_sal=Toplevel()
	ventana_inv_sal.config(bg='#3A6F9B')
	ventana_inv_sal.geometry('1010x630+160+20')
	fuente=("Arial",12,"bold")

	#boton salir usuario
	salir_inv = Button(ventana_inv_sal,text='V\nO\nL\nV\nE\nR',command=ventana_inv_sal.destroy,font=('Arial',14,'bold'),bg='#0187FD',fg='#FFF')
	salir_inv.place(x=973,y=440)

	#parametro base de ventana inventario
	base_inv = Frame(ventana_inv_sal)
	base_inv.configure(width=998,height=282,bg='#0187FD')
	base_inv.place(x=5,y=6)

	#parametro base de ventana inventario
	base_inv_insert=Frame(ventana_inv_sal)
	base_inv_insert.configure(width=402,height=250,bg='#03A9F4')
	base_inv_insert.place(x=15,y=10)
	titulo_base_inv_insert=Label(base_inv_insert,text='REGISTRAR MOVIMIENTO DE SALIDA',font=fuente,bg='#03A9F4',fg='#FFF').place(x=40,y=1)

	#parametro base de ventana inventario
	base_inv_update=Frame(ventana_inv_sal)
	base_inv_update.configure(width=542,height=250,bg='#03A9F4')
	base_inv_update.place(x=425,y=10)
	titulo_base_inv_update=Label(base_inv_update,text='MODIFICAR MOVIMIENTO DE SALIDA',font=fuente,bg='#03A9F4',fg='#FFF').place(x=130,y=1)

	#variables para capturar el valor en los inputs(Entry)
	ins_producto=StringVar()
	ins_stock=IntVar()
	ins_descrip=StringVar()
	ins_id=IntVar()
	ins_stock_proc=IntVar()

	#formulario ingresar 
	titulo_nom=Label(base_inv_insert,width=10,text='Producto').place(x=223,y=40)
	inv_ins_nom=Entry(base_inv_insert,width=40,state='disable',textvariable=ins_producto).place(x=55,y=60)

	titulo_stock=Label(base_inv_insert,text='Cantidad a Retirar').place(x=199,y=90)
	inv_ins_nom=Entry(base_inv_insert,width=40,textvariable=ins_stock).place(x=55,y=110)

	titulo_desc=Label(base_inv_insert,text='Observacion Retiro').place(x=193,y=140)
	inv_ins_nom=Entry(base_inv_insert,width=40,textvariable=ins_descrip).place(x=55,y=160)

	titulo_id=Label(base_inv_insert,width=5,text='ID').place(x=78,y=190)
	inv_ins_nom=Entry(base_inv_insert,width=10,state='disable',textvariable=ins_id).place(x=55,y=210)

	#funcion para insertar datos
	def ins_sal_inv():
		try:
			descri_pro=ins_descrip.get().lower()
			id_pro=int(ins_id.get())
			stock_pro=int(ins_stock.get())
			stock_pro_list=int(ins_stock_proc.get())
			
			if stock_pro <= 0:
				tkinter.messagebox.showinfo("Mensaje","El campo Stock no acepta valores 0 y negativos")
				ventana_inv_sal.destroy()
				listar_inventario_salida()
			else:	
				if stock_pro > stock_pro_list:
					tkinter.messagebox.showinfo("Mensaje","La cantidad de salida no debe ser superior al stock actual")
					ventana_inv_sal.destroy()
					listar_inventario_salida()
				else:
					if descri_pro and id_pro and stock_pro:
						con.cursor.execute("INSERT INTO salidas(id_salida, fecha_salida, descripcion_salida, id_producto, cantidad) VALUES (null,now(),%s,%s,%s)",(descri_pro,id_pro,stock_pro))
						con.conexion.commit()
						ventana_inv_sal.destroy()

						tkinter.messagebox.showinfo("mensaje","Datos Guardados")
						listar_inventario_salida()
					else:
						tkinter.messagebox.showinfo("mensaje","No ingrese campos en blanco\n\
		Llene todos los campos del Formulario")
						ventana_inv_sal.destroy()
						listar_inventario_salida()
		except ValueError:
			tkinter.messagebox.showinfo("mensaje","EL CAMPO STOCK ACEPTAN SÓLO VALORES \
                NUMÉRICOS NO INGRESE LETRAS")
			ventana_inv_sal.destroy()
			listar_inventario_salida()
		except:
			tkinter.messagebox.showinfo('mensaje','Error')
			ventana_inv_sal.destroy()
			listar_inventario_salida()
	#boton para ingresar entreda de inventario
	bot_ins_inv=Button(base_inv_insert,text='Ingresar',command=ins_sal_inv).place(x=223,y=200)

	#parametro grafico de lista de inventario
	base_lista_inv=Frame(ventana_inv_sal)
	base_lista_inv.place(x=14,y=287)

	title_prod=Label(base_inv,width=20,text='LISTADO DE PRODUCTOS').place(x=10,y=260)
	tit_doblecl=Label(base_inv,text='Seleccione con Doble Click').place(x=264,y=260)

	inv = ttk.Treeview(base_lista_inv,height=15,selectmode="browse",columns=('ID','PRODUCTO','STOCK','DESCRIPCION','IDESTADO'))
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
	inv.column("IDESTADO",width=1,minwidth=0)

	query1="SELECT 	idProductos,nombre_producto,stock_producto,descripcion_producto, Estado_idEstado FROM productos WHERE Estado_idEstado=1  ORDER BY nombre_producto ASC"
	con.cursor.execute(query1)
	filas = con.cursor.fetchall()

	for col in filas:
		inv.insert('','end',values=(str(col[0])+'\t',str(col[1]),str(col[2]),str(col[3]),str(col[4])))
	inv.pack()
	
	#funcion para selecionar lista de producto 
	def selecSal_inv(a):
		curSal_inv=inv.focus()

		asa=inv.set(curSal_inv,"ID")
		ese=inv.set(curSal_inv,"PRODUCTO")
		isi=inv.set(curSal_inv,"DESCRIPCION")
		oso=inv.set(curSal_inv,"STOCK")
		ins_id.set(asa)
		ins_producto.set(ese)
		ins_stock_proc.set(oso)
	#evento doble click para seleccionar datos en la lista
	inv.bind('<Double-1>',selecSal_inv)

#********************************************************************
	
	#variables para capturar el valor en los inputs(Entry)
	up_producto=StringVar()
	up_stock=IntVar()
	up_descrip=StringVar()
	up_id=IntVar()
	up_stock_list=IntVar()
	#formulario de modificar
	titulo_nom=Label(base_inv_update,width=10,text='Producto').place(x=323,y=40)
	inv_update_nombre=Entry(base_inv_update,width=40,state='disable',textvariable=up_producto).place(x=155,y=60)

	titulo_stock=Label(base_inv_update,text='Modificar Cantidad a Retirar').place(x=245,y=90)
	inv_update_nombre=Entry(base_inv_update,width=40,textvariable=up_stock).place(x=155,y=110)

	titulo_desc=Label(base_inv_update,text='Modificar Observaciones Retiro').place(x=228,y=140)
	inv_update_nombre=Entry(base_inv_update,width=40,textvariable=up_descrip).place(x=155,y=160)

	titulo_id=Label(base_inv_update,width=5,text='ID').place(x=178,y=190)
	inv_update_nombre=Entry(base_inv_update,width=10,state='disable',textvariable=up_id).place(x=155,y=210)

	#funcion para modificar listado de inventario
	def mod_inv_sal():
		try:
			descri_up=up_descrip.get().lower()
			id_up=int(up_id.get())
			stock_up=int(up_stock.get())
			stock_up_list=int(up_stock_list.get()) 
			print(stock_up_list)

			if stock_up <= 0:

				
				tkinter.messagebox.showinfo("Mensaje","El campo Stock no acepta valores 0 y negativos")
				ventana_inv_sal.destroy()
				listar_inventario_salida()
			
					
			else:
			
				if descri_up and id_up and stock_up:
					
					con.cursor.execute("UPDATE salidas SET descripcion_salida=%s, cantidad=%s WHERE id_salida=%s",(descri_up,stock_up,id_up))

					ventana_inv_sal.destroy()
					tkinter.messagebox.showinfo("mensaje","Datos Guardados")
					listar_inventario_salida()
					
					f = con.cursor.execute('SELECT stock_producto from productos where idProductos= %s',(id_up))
					if f<0:
						con.conexion.rollback()
						tkinter.messagebox.showinfo("mensaje","Stock menor  a cero")

						
				else:
					tkinter.messagebox.showinfo("mensaje","No ingrese campos en blanco\n\
	Llene todos los campos del Formulario")
					ventana_inv_sal.destroy()
					listar_inventario_salida()
		except ValueError:
			tkinter.messagebox.showinfo("mensaje","EL CAMPO STOCK ACEPTAN SÓLO VALORES \
                NUMÉRICOS NO INGRESE LETRAS")
			ventana_inv_sal.destroy()
			listar_inventario_salida()
		except:
			tkinter.messagebox.showinfo('mensaje','Error')
			ventana_inv_sal.destroy()
			listar_inventario_salida()	

	#boton para modificar entrada de inventario
	bot_upd_inv=Button(base_inv_update,text='Ingresar',command=mod_inv_sal).place(x=293,y=200)

	#parametro grafico de lista de entrada de inventario
	base_lista_inv=Frame(ventana_inv_sal)
	base_lista_inv.place(x=425,y=287)
	
	title_prod=Label(base_inv,text='REGISTRO DE SALIDAS REALIZADAS').place(x=421,y=260)
	tit_doblecl=Label(base_inv,text='Seleccione con Doble Click').place(x=814,y=260)

	#parametro graficos de lista de entrada de inventario
	inv_up=ttk.Treeview(base_lista_inv,height=15,selectmode="browse",columns=('ID','FECHA','DESCRIPCION','IDPRODUCTO','PRODUCTO','STOCK','STOCK_PRODUCTO'))
	inv_up.pack(expand=YES, fill=BOTH)

	inv_up.heading('#0',text='')
	inv_up.column('#0',width=0,minwidth=0)

	inv_up.heading("ID",text="ID")
	inv_up.column("ID",width=35,anchor=CENTER)

	inv_up.heading("FECHA",text="FECHA SALIDA")
	inv_up.column("FECHA",width=115,anchor=CENTER)

	inv_up.heading("DESCRIPCION",text="OBSERVACION RETIRO")
	inv_up.column("DESCRIPCION",width=140,anchor=CENTER)

	inv_up.heading("IDPRODUCTO",text="IDPRODUCTO")
	inv_up.column("IDPRODUCTO",width=0,minwidth=0)

	inv_up.heading("PRODUCTO",text="PRODUCTO")
	inv_up.column("PRODUCTO",width=115,anchor=CENTER)

	inv_up.heading("STOCK",text="CANTIDAD RETIRADA")
	inv_up.column("STOCK",width=35,anchor=CENTER)

	inv_up.heading("STOCK_PRODUCTO",text="STOCK_PRODUCTO")
	inv_up.column("STOCK_PRODUCTO",width=120,minwidth=0)


	query1="SELECT id_salida, fecha_salida, descripcion_salida, id_producto, nombre_producto, cantidad, productos.stock_producto  FROM salidas INNER JOIN productos ON salidas.id_producto=productos.idProductos ORDER BY fecha_salida DESC"

	con.cursor.execute(query1)
	filas = con.cursor.fetchall()

	for col in filas:
		inv_up.insert('','end',values=(str(col[0])+'\t',str(col[1]),str(col[2]),str(col[3]),str(col[4]),str(col[5]),str(col[6])))

	inv_up.pack()

	#funcion para selecionar lista de producto 
	def selecSal_inv_up(b):
		curSal_inv_up=inv_up.focus()

		asa1=inv_up.set(curSal_inv_up,"ID")
		ese2=inv_up.set(curSal_inv_up,"PRODUCTO")
		isi3=inv_up.set(curSal_inv_up,"DESCRIPCION")
		oso4=inv_up.set(curSal_inv_up,"STOCK")
		usu5=inv_up.set(curSal_inv_up,"STOCK_PRODUCTO")

		up_id.set(asa1)
		up_producto.set(ese2)
		up_descrip.set(isi3)
		up_stock.set(oso4)
		up_stock_list.set(usu5)

	#evento doble click para seleccionar datos en la lista
	inv_up.bind('<Double-1>',selecSal_inv_up)

	