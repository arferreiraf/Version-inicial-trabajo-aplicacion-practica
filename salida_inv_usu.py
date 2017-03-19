from tkinter import*
import conexion as con
from tkinter import ttk
import tkinter.messagebox

def sal_producto():
	global vent_sal
	#parametro base de ventana de salida de inventario
	vent_sal=Toplevel()
	vent_sal.config(bg='#3A6F9B')
	vent_sal.geometry('900x650+230-50')
	fuente=("Arial", 12, "bold")

	#boton salir ventana salida inventario
	salir=Button(vent_sal,text='V\nO\nL\nV\nE\nR',command=vent_sal.destroy,font=('Arial',14,'bold'),bg='#0187FD',fg='#FFF')
	salir.place(x=825,y=462)

	#parametro base de ventana inventario
	base_inv = Frame(vent_sal)
	base_inv.configure(width=890,height=365,bg='#0187FD')
	base_inv.place(x=5,y=6)
	tit_doblecl = Label(base_inv,text='Seleccione con Doble Click').place(x=730,y=13)

	#parametro base de ventana inventario
	base_inv_insert=Frame(base_inv)
	base_inv_insert.configure(width=350,height=356,bg='#0077B9')
	base_inv_insert.place(x=60,y=5)
	titulo_base_inv_insert=Label(base_inv_insert,text='REGISTRAR MOVIMIENTO DE SALIDA',font=fuente,bg='#0077B9',fg='#FFF').place(x=12,y=1)

	#variables para capturar el valor en los inputs(Entry)
	ins_producto=StringVar()
	ins_stock=IntVar()
	ins_descrip=StringVar()
	ins_id=IntVar()
	ins_stock_proc=IntVar()

	#formulario ingresar 
	titulo_nom=Label(base_inv_insert,width=10,text='Producto').place(x=223,y=40)
	inv_ins_nom=Entry(base_inv_insert,width=40,state='disable',textvariable=ins_producto).place(x=55,y=60)

	titulo_stock=Label(base_inv_insert,width=16,text='Cantidad a Retirar').place(x=181,y=90)
	inv_ins_nom=Entry(base_inv_insert,width=40,textvariable=ins_stock).place(x=55,y=110)

	titulo_desc=Label(base_inv_insert,width=17,text='Observacion Retiro').place(x=174,y=140)
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
				vent_sal.destroy()
				sal_producto()
			else:	
				if stock_pro > stock_pro_list:
					tkinter.messagebox.showinfo("Mensaje","La cantidad de salida no debe ser superior al stock actual")
					vent_sal.destroy()
					sal_producto()
				else:
					if descri_pro and id_pro and stock_pro:
						con.cursor.execute("INSERT INTO salidas(id_salida, fecha_salida, descripcion_salida, id_producto, cantidad) VALUES (null,now(),%s,%s,%s)",(descri_pro,id_pro,stock_pro))
						con.conexion.commit()
						vent_sal.destroy()

						tkinter.messagebox.showinfo("mensaje","Datos Guardados")
						sal_producto()
					else:
						tkinter.messagebox.showinfo("mensaje","No ingrese campos en blanco\n\
		Llene todos los campos del Formulario")
						vent_sal.destroy()
						sal_producto()
		except ValueError:
			tkinter.messagebox.showinfo("mensaje","EL CAMPO STOCK ACEPTAN SÓLO VALORES \
                NUMÉRICOS NO INGRESE LETRAS")
			vent_sal.destroy()
			sal_producto()
		except:
			tkinter.messagebox.showinfo('mensaje','Error')
			vent_sal.destroy()
			sal_producto()
	#boton para ingresar entreda de inventario
	bot_ins_inv=Button(base_inv_insert,text='Ingresar',command=ins_sal_inv).place(x=223,y=200)

	#parametro grafico de lista de inventario
	base_lista_inv=Frame(vent_sal)
	base_lista_inv.place(x=435,y=40)
	tit_tree11 = Label(base_inv,width=20,text='LISTADO DE PRODUCTOS').place(x=432,y=14)

	inv = ttk.Treeview(base_lista_inv,height=15,selectmode="browse",columns=('ID','PRODUCTO','STOCK','DESCRIPCION','IDESTADO'))
	inv.pack(expand=YES, fill=BOTH)

	inv.heading('#0',text='')
	inv.column('#0',width=0,minwidth=0)

	inv.heading("ID",text="ID")
	inv.column("ID",width=50,anchor=CENTER)

	inv.heading("PRODUCTO",text="PRODUCTO")
	inv.column("PRODUCTO",width=130,anchor=CENTER)

	inv.heading("STOCK",text="STOCK ACTUAL")
	inv.column("STOCK",width=95,anchor=CENTER)

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

#***********************************************************
	
	#parametro grafico de lista de entrada de inventario
	base_lista_inv=Frame(vent_sal)
	base_lista_inv.place(x=100,y=400)
	tit_tree2 = Label(vent_sal,text='REGISTRO DE SALIDAS REALIZADAS').place(x=100,y=379)

	#parametro graficos de lista de entrada de inventario
	inv_up=ttk.Treeview(base_lista_inv,height=11,selectmode="browse",columns=('ID','FECHA','DESCRIPCION','IDPRODUCTO','PRODUCTO','STOCK'))
	inv_up.pack(expand=YES, fill=BOTH)

	inv_up.heading('#0',text='')
	inv_up.column('#0',width=0,minwidth=0)

	inv_up.heading("ID",text="ID")
	inv_up.column("ID",width=50,anchor=CENTER)

	inv_up.heading("FECHA",text="FECHA SALIDA")
	inv_up.column("FECHA",width=130,anchor=CENTER)

	inv_up.heading("DESCRIPCION",text="OBSERVACION RETIRO")
	inv_up.column("DESCRIPCION",width=250,anchor=CENTER)

	inv_up.heading("IDPRODUCTO",text="IDPRODUCTO")
	inv_up.column("IDPRODUCTO",width=0,minwidth=0)

	inv_up.heading("PRODUCTO",text="PRODUCTO")
	inv_up.column("PRODUCTO",width=130,anchor=CENTER)

	inv_up.heading("STOCK",text="CANTIDAD RETIRADA")
	inv_up.column("STOCK",width=150,anchor=CENTER)


	query1="SELECT id_salida, fecha_salida, descripcion_salida, id_producto, nombre_producto, cantidad  FROM salidas INNER JOIN productos ON salidas.id_producto=productos.idProductos ORDER BY fecha_salida DESC"

	con.cursor.execute(query1)
	filas = con.cursor.fetchall()

	for col in filas:
		inv_up.insert('','end',values=(str(col[0])+'\t',str(col[1]),str(col[2]),str(col[3]),str(col[4]),str(col[5])))

	inv_up.pack()

