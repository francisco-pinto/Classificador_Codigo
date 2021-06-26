# -*- coding: utf-8 -*-
"""
Created on Thu Jun  3 14:44:44 2021

@author: ffcpi
"""

import MySQLdb
import time
import patoolib
import os
from ctypes import *
import subprocess

uploadURL = "C:/wamp64/www/Projeto/Uploads"
ExtractedFilesURL = "C:/wamp64/www/Projeto/Projeto_Python/Extracted_Files"
fileToExecute = ExtractedFilesURL + "/" + "FicheiroCorretor.php";

servername = "127.0.0.1"
databaseName = "Classificador_Codigo"
username = "root"
password = ""


#Connect DB

db = MySQLdb.connect(host=servername,    # your host, usually localhost
                     user=username,         # your username
                     passwd=password,  # your password
                     db=databaseName)        # name of the data base

# you must create a Cursor object. It will let
#  you execute all the queries you need
cur = db.cursor()


#File Path from the uploaded files
filesPath = uploadURL


#Programa corre infintamente
#é sempre corrigido o primeiro ficheiro
#das pastas zipadas e depois é retirado
#destas e colocado o ficheiro final noutra pasta




#Get file names
files = []
for (dirpath, dirnames, filenames) in os.walk(filesPath):
    files.extend(filenames)
    break
  
    
fileIndex = 0

for file in files: 

    start_time = time.time()    

      
    print(file)
        
    
    #Get project ID to get the casos_Teste
    cur.execute("SELECT ProjetoID FROM ficheiro where Nome=%s", (file, ))
    
    for row in cur.fetchall():
        print("Projeto ID: ", row[0])
        projectID = row[0]
    
    
    
    
    
    #Get Casos_Teste
    cur.execute("SELECT Output FROM casos_teste where ProjetoID=%s", (projectID, ))
    GetOutputs = []
    for row in cur.fetchall():
        GetOutputs.extend(row)
    
    #Bug thath give " " in the begining of the array in the database
    Outputs = []
    Outputs = [i.strip(' ') for i in GetOutputs]
    
    
    #print("\n\nOutputs: ", Outputs)
       
    
    
    cur.execute("SELECT Input FROM casos_teste where ProjetoID=%s", (projectID, ))
    Inputs = []
    for row in cur.fetchall():
        Inputs.extend(row)
    
    #print("\n\nInputs: ", Inputs)
    
    
    
    
    
    #Get User ID
    userID = []
    cur.execute("SELECT UtilizadorID FROM ficheiro where ProjetoID=%s", (projectID, ))
    for row in cur.fetchall():
        userID.extend(row)
    
    #print("UserID: ", userID)
    
    
    
    #This variable is not in use for now
    #Get main file 
    main_file = []
    cur.execute("SELECT MainFile FROM ficheiro where ProjetoID=%s", (projectID, ))
    for row in cur.fetchall():
        main_file.extend(row)
    
    
    
    
    #Extract the file
    fileToExtract = filesPath + "/" + file
    
    #Extract file
    try:
        patoolib.extract_archive(fileToExtract, outdir=ExtractedFilesURL) 
    except:
        #Means that a file already exists there 
        if os.path.exists(ExtractedFilesURL):
            os.remove(ExtractedFilesURL)



    #Get Extracted file names
    ExtractedFiles = []
    for (dirpath, dirnames, filenames) in os.walk(ExtractedFilesURL):
        ExtractedFiles.extend(filenames)
        break
    
    #print("\n\n", ExtractedFiles)
    
    
    
    
    
    # #Create the file that will test
    studentFile = ExtractedFilesURL + "/" + ExtractedFiles[fileIndex]
    phpURL = "C:\\wamp64\\bin\\php\\php7.4.9\\php.exe"
    #print("Path do ficheiro a executar: ", fileToExecute)
        
    FinalFileName = ExtractedFiles[fileIndex].split(".");

  
    #Create new File
    print("\n\n\n\n\n", str(studentFile))
    
    fich = open(fileToExecute, "w");
    fich.write('<?php\n')
    fich.write('include "' + studentFile +'";\n\n') 
    fich.write('$resultado=sum($argv[1]);\n\n')
    fich.write('exit($resultado);\n')
    fich.write('?>\n')
    #inclui ficheiro do aluno
    fich.close()
    
    
    #File that is going to call the student file
    #fileToExecute = ExtractedFilesURL + "/" + ExtractedFiles[0] + "Final";
    
    
    
    
    #Input = [1, 2]
    #Get the results
    #("Numero de inputs: " + str(len(Inputs)))
    i = 0
    OutputsObtidos = []
    for row in Inputs:
        print("Input: " + str(row))
        proc = subprocess.Popen([phpURL, fileToExecute, " " + str(row)], shell=True, stdout=subprocess.PIPE)
        output = proc.stdout.read()
        OutputsObtidos.extend(output)
        #print("Output: " + str(OutputsObtidos[i]))
        #i+=1

#Valores estão a ficar duplicados. Para evitar isso
    # OutputsObtidosFinais = []
    # i = 0
    # for row in OutputsObtidos:
    #         if(i % 2 == 0)
    #             OutputsObtidosFinais.extend(row)
    #         i++

    print("Outputs obtidos:")
    print(OutputsObtidos)    

    #Convert from ascii to string
    OutputsObtidosFinais = ''.join(chr(i) for i in OutputsObtidos)
    print((OutputsObtidosFinais[0]))
    
    #Compare the results
    numOutputsTotais = len(OutputsObtidos)
    correctOutputs = 0
    index = 0   
    
    #Output = [3, 4]
    print("Número de outputs obtidos finais: " + str(OutputsObtidosFinais))
    
    
    for row in OutputsObtidosFinais:
        print("\rOutput esperado: " + str(Outputs[index]))
        print("\rOutput Obtido: " + str(row))
        if(str(Outputs[index]) == str(row)):
            correctOutputs+=1
        
        index+=1


    #Get grade
    gradePerQuestion = 20/index
    finalGrade = gradePerQuestion*correctOutputs
    
    print("A sua nota é: " + str(finalGrade))
        
    
    #Save values in Database
    try:
        #cur = db.cursor()
    
        sql = "INSERT INTO nota (Classificacao, UtilizadorID) VALUES (%s , %s)"
        
        
        #Erro na inserção dos valores. val dá erro
        val = (finalGrade, userID[0])
        cur.execute(sql, val)
        
        db.commit()
            
        print("Nota inserida com sucesso")
    except:
        print("Erro ao inserir na BD")
    
    
    print("___________________")
    print("___________________")
    print("___________________")
    print("___________________")
    print("___________________")
    print("___________________")
    print("___________________")
    print("___________________")
    print("___________________")
    
    



    print("--- %s seconds ---\n\n\n" % (time.time() - start_time))

    fileIndex = fileIndex+1
    
#Close DB
db.close()