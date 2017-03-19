from tkinter import*
import conexion as con
from tkinter import ttk
import tkinter.messagebox

def ent_producto():
	global ventana_ent
	#parametro base de ventana de entrada de inventario
	ventana_ent=Toplevel()
	ventana_ent.config(bg='#3A6F9B')
	ventana_ent.geometry('900x650+230-50')
	fuente=("Arial", 12, "bold")

	#boton salir ventana entrada inventario
	salir=Button(ventana_ent,text='V\nO\nL\nV\nE\nR',command=ventana_ent.destroy,font=('Arial',14,'bold'),bg='#0187FD',fg='#FFF')
	salir.place(x=825,y=462)

	#parametro base de entrada inventario
	base_inv = Frame(ventana_ent)
	base_inv.configure(width=890,height=365 ,bg='#0187FD')
	base_inv.place(x=5,y=6)
	tit_doblecl = Label(base_inv,text='Seleccione con Doble Click').place(x=730,y=13)

	#parametro base de ventana de entrada de inventario
	base_inv_insert=Frame(base_inv)
	base_inv_insert.configure(width=350,height=356,bg='#0077B9')
	base_inv_insert.place(x=60,y=5)
	title_base_inv_insert=Label(base_inv_insert,text='REGISTRAR MOVIMIENTO DE ENTRADA',font=fuente,bg='#0077B9',fg='#FFF').place(x=12,y=1)

	#Variables para capturar el valor en los inputs(Entry)
	ins_producto=StringVar()
	ins_stock=IntVar()
	ins_descrip=StringVar()
	ins_id=IntVar()

	##FORMULARIO INGRESAR
	titulo_nom=Label(base_inv_insert,width=10,text='Producto').place(x=223,y=40)
	inv_insert_nombre=Entry(base_inv_insert,width=40,state='disable',textvariable=ins_producto).place(x=55,y=60)

	titulo_stock=Label(base_inv_insert,width=16,text='Cantidad a Ingresar').place(x=181,y=90)
	inv_insert_stock=Entry(base_inv_insert,width=40,textvariable=ins_stock).place(x=55,y=110)

	titulo_desc=Label(base_inv_insert,width=17,text='Observaciones Ingreso').place(x=174,y=140)
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
				ventana_ent.destroy()
				ent_producto()
			else:	
				if descri_pro and id_pro and stock_pro:
					con.cursor.execute("INSERT INTO ingresos(id_ingreso, fecha_ingreso, descripcion_ingreso, id_producto, cantidad) VALUES (null,now(),%s,%s,%s)",(descri_pro,id_pro,stock_pro))
					con.conexion.commit()
					ventana_ent.destroy()

					tkinter.messagebox.showinfo("mensaje","Datos Guardados")
					ent_producto()
				else:
					tkinter.messagebox.showinfo("mensaje","No ingrese campos en blanco\n\
	Llene todos los campos del Formulario")
					ventana_ent.destroy()
					ent_producto()
		except ValueError:
			tkinter.messagebox.showinfo("mensaje","EL CAMPO STOCK ACEPTAN SÓLO VALORES \
                NUMÉRICOS NO INGRESE LETRAS")
			ventana_ent.destroy()
			ent_producto()
		except:
			tkinter.messagebox.showinfo('mensaje','Error')
			ventana_ent.destroy()
			ent_producto()
	#boton para ingresar entrada de inventario
	bot_ins_inv=Button(base_inv_insert,text='Ingresar',command=ins_inv).place(x=223,y=200)

	#parametro grafico de lista de entrada de inventario
	base_lista_inv=Frame(ventana_ent)
	base_lista_inv.place(x=435,y=40)
	
	tit_tree11 = Label(base_inv,width=20,text='LISTADO DE PRODUCTOS').place(x=432,y=14)
	#parametro graficos de lista de entrWIDTHada de inventario
	inv=ttk.Treeview(base_lista_inv,height=15,selectmode="browse",columns=('ID','PRODUCTO','STOCK','DESCRIPCION','IDESTADO'))
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

#******************************************************
	#parametro grafico de lista de entrada de inventario
	base_lista_inv=Frame(ventana_ent)
	base_lista_inv.place(x=100,y=400)

	tit_tree2 = Label(ventana_ent,text='REGISTRO DE INGRESOS REALIZADOS').place(x=100,y=379)
	
	#parametro graficos de lista de entrada de inventario
	inv_up=ttk.Treeview(base_lista_inv,height=11,selectmode="browse",columns=('ID','FECHA','DESCRIPCION','IDPRODUCTO','PRODUCTO','STOCK'))
	inv_up.pack(expand=YES, fill=BOTH)

	inv_up.heading('#0',text='')
	inv_up.column('#0',width=0,minwidth=0)

	inv_up.heading("ID",text="ID")
	inv_up.column("ID",width=50,anchor=CENTER)

	inv_up.heading("FECHA",text="FECHA INGRESO")
	inv_up.column("FECHA",width=130,anchor=CENTER)

	inv_up.heading("DESCRIPCION",text="OBSERVACION INGRESO")
	inv_up.column("DESCRIPCION",width=250,anchor=CENTER)

	inv_up.heading("IDPRODUCTO",text="IDPRODUCTO")
	inv_up.column("IDPRODUCTO",width=0,minwidth=0)

	inv_up.heading("PRODUCTO",text="PRODUCTO")
	inv_up.column("PRODUCTO",width=130,anchor=CENTER)

	inv_up.heading("STOCK",text="CANTIDAD INGRESADA")
	inv_up.column("STOCK",width=150,anchor=CENTER)


	query1="SELECT id_ingreso, fecha_ingreso, descripcion_ingreso, id_producto, nombre_producto, cantidad  FROM ingresos INNER JOIN productos ON ingresos.id_producto=productos.idProductos ORDER BY fecha_ingreso DESC"

	con.cursor.execute(query1)
	filas = con.cursor.fetchall()

	for col in filas:
		inv_up.insert('','end',values=(str(col[0])+'\t',str(col[1]),str(col[2]),str(col[3]),str(col[4]),str(col[5])))

	inv_up.pack()